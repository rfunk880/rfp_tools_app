<?php
namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Exceptions\ApplicationException;
use App\Models\Permission;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.onlyAdmin');
    }

    public function index()
    {
        // onlyIf(isAdmin());
        $roles = Role::with('permissions')->get();
        // dd($roles);
        // $modules =  collect(json_decode(file_get_contents(storage_path('app/permissions.json'))));
        $modules = Permission::all();
        return sysView('users.roles', compact('roles', 'modules'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|unique:roles'
        ]);
        // $data['status'] = 1;
        $role = Role::create($data);
        // permission_INITIALIZE_ROLE($role);
        return redirect()->back()->with(['message' => 'Role Added.']);
    }

    public function bulkUpdate(Request $request)
    {
        $data = $this->validate($request, [
            'permissions' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            // Role::where('id', ">", 0)->update(['permissions' => null]);
            DB::table('role_has_permissions')->truncate();
            foreach ($data['permissions'] as $roleId => $permissions) {
                // dd($permissions);
                $role = Role::find($roleId);
                if ($role) {
                    /* $role->permissions = [];
                    $role->save(); */
                    $role->syncPermissions(array_keys($permissions));
                    // $role->save();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
        // dd($data['permissions']);
        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Roles Updated'])
        ]);
        //return redirect()->back()->with(['message' => 'Permission Saved.']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail(decryptIt($id));
        $role->delete();
        return redirect()->back()->with(['message' => 'Deleted']);
    }

    public function bulkPositionUpdate()
    {
        if (!request('role')) {
            throw new ApplicationException("Invalid Request");
        }
        $widgets = array_reverse(request('role'));
        foreach ($widgets as $position => $id) {
            Role::where(['id' => $id])->update(['position' => $position]);
        }
        return response("OK");
    }
}
