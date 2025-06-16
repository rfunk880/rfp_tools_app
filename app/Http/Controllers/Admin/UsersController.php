<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserType;
use App\Events\UserSaved;
use Illuminate\Http\Request;
use Support\DTO\MailAddress;
use Support\Mails\EmailService;
use Support\Traits\UploaderTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Validators\UserValidator;
use Illuminate\Validation\Rules\Password;
use Support\Exceptions\ApplicationException;
use Illuminate\Contracts\Auth\PasswordBroker;
use App\Repositories\Criteria\UserQuickSearchCriteria;
use Illuminate\Contracts\Queue\EntityNotFoundException;

class UsersController extends Controller
{
    use UploaderTrait;
    private $users;

    public function __construct()
    {
        $this->middleware('auth.onlyAdmin', [
            'except' => [
                'getProfile',
                'postProfile',
                'getInfo',
                'postInfo',
                'getChangePassword',
                'postChangePassword',
                'clearLogin',
            ]
        ]);
        $this->users = User::query();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->getList();
        }
        return sysView('users.index');
    }

    public function getList()
    {
        $users = User::filter(request()->all())->exceptMe()
            ->orderBy(request('orderBy', 'name'), request('orderType', 'ASC'))->paginate(10);
        return response()->json([
            'data' => sysView('users.partials.list', compact('users'))->render(),
            'pagination' => view('components.pagination', ['data' => $users])->render()
        ]);
    }

    public function create()
    {
        return sysView('users.create');
    }

    public function store(Request $request, UserValidator $validator)
    {
        $validator->with($data = $request->all())->validate();
        $password = null;
        if ($data['password'] != '') {
            $password = $data['password'];
            $data['password'] = bcrypt($data['password']);
        }
        if ($user = $this->users->create($data)) {
            DB::transaction(function () use ($user) {});
            $user->markEmailAsVerified();
            if ($request->role_id) {
                $user->syncRoles([$request->role_id]);
            }
            event("user.saved", [$user, false, $password]);
            return response()->json([
                'redirect' => sysRoute('users.index'),
                'notification' => ReturnNotification(['success' => 'User has been created.'])
            ]);
        }
        throw new ApplicationException("Can Not add user");
    }

    public function show($id)
    {
        $user = $this->users->findOrFail($id);
    }

    public function edit($id)
    {
        $user = $this->users->findOrFail(decryptIt($id));
        return sysView('users.edit', compact('user'));
    }

    public function update(UserValidator $validator, $id)
    {
        $id = decryptIt($id);
        $validator->with($data = request()->all())->forContext('edit')->validate();
        if (@$data['password'] == '') {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $user = $this->users->findOrFail($id);
        $oldStatus = $user->status;
        $user->fill($data);
        $user->save();
        if (request('role_id')) {
            $user->syncRoles([request('role_id')]);
        }
        if (request('switch_roles', false)) {
            $user->saveSwitchableRoles(request('switchable_user_types', []), $user->user_type_id);
        } else {
            $user->saveSwitchableRoles([], $user->user_type_id);
        }
        return response()->json([
            'redirect' => sysRoute('users.index'),
            'notification' => ReturnNotification(['success' => 'User saved.'])
        ]);
    }

    public function destroy($id)
    {
        $user = $this->users->findOrFail(decryptIt($id));
        if ($user->selfDestruct()) {
            return ajaxSuccess([], "Removed");
        }
        abort(500, 'INVALID');
    }

    public function getProfile()
    {
        $user = $this->users->findOrFail(auth()->user()->id);
        return sysView('users.profile', compact('user'));
    }

    public function postProfile(UserValidator $userValidator)
    {
        $userValidator->with($userData = request()->all())->setDefault('profile')->validate();
        unset($userData['email']);
        unset($userData['user_type_id']);
        $user = auth()->user();
        $user->fill($userData);
        $user->save();
        if (request()->file('image')) {
            $user->addMediaFromRequest('image')->toMediaCollection('avatar');
        }
        return back()->with(array('success' => 'Profile has been updated successfully'));
    }

    public function getInfo()
    {
        $user = $this->users->findOrFail(auth()->user()->id);
        return sysView('users.info', compact('user'));
    }

    public function postInfo(UserValidator $userValidator)
    {
        $userValidator->with($userData = request()->all())->setDefault('info')->validate();
        unset($userData['email']);
        unset($userData['user_type_id']);
        if ($this->users->update($userData, auth()->user()->id)) {
            return back()->with(array('success' => 'Profile has been updated successfully'));
        } else {
            redirect()->back()->with(array('error', 'Sorry! cannot perform the requested action at the moment.'));
        }
    }

    public function getChangePassword()
    {
        return sysView('users.changepassword');
    }

    public function postChangePassword(Request $request)
    {
        $this->validate($request, [
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ]
        ]);
        if (auth()->user()->changePassword(request()->get('password'))) {
            return back()->with(array('success' => 'User Password Changed Successfully'));
        } else {
            back()->with(array('error', 'Sorry! cannot perform the requested action at the moment'));
        }
    }

    public function getResetPassword(Guard $auth, PasswordBroker $passwords, $id)
    {
        $user = $this->users->findOrFail(decrypt($id));
        if (!$user) {
            throw new ApplicationException("Invalid User Data");
        }
        $password = randomPassword();
        if ($user->changePassword($password)) {
            $emailService = new EmailService();
            $emailService->subject('Password reset')->to(new MailAddress($user->email))
                ->send('emails.users.password-reset', compact('user', 'password'));
            return redirect()->route('webpanel.users.index')->with('success', 'Password Reset Successful');
        }
        throw new ApplicationException("Opps! Something went wrong. Please try again later");
    }

    public function loginAs($id)
    {
        session()->put('ADMIN_IMPERSONATING', authId());
        auth()->logout();
        auth()->loginUsingId(decryptIt($id));
        return redirect(config('fortify.home'));
    }

    public function clearLogin(Request $request)
    {
        if ($id = session('ADMIN_IMPERSONATING')) {
            session()->forget('ADMIN_IMPERSONATING');
            auth()->loginUsingId($id);
            return redirect(config('fortify.home'));
        }
        auth()->logout();
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return redirect('login');
    }


    public function switchRole(Request $request)
    {
        $user = auth()->user();
        if ($request->user_type_id) {
            $roles = $user->getSwitchableRoles();
            if (in_array($request->user_type_id, $roles) && $request->user_type_id != UserType::ADMIN) {
                $user->user_type_id = $request->user_type_id;
                $user->save();
                return redirect('webpanel/dashboard')->with(['success' => 'Role Switched']);
            }
        }
        return redirect()->back()->with(['error' => 'Cannot switch role.']);
    }
}
