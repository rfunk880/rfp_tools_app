<?php
namespace App\Http\Controllers\Admin;

use App\Module;
use App\Models\Tag;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Imports\FacilitiesImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Support\Exceptions\ApplicationException;
use Illuminate\Database\Eloquent\Relations\Relation;

class FacilitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.onlyAdmin', [
            'except' => ['index', 'show']
        ]);
    }
    public function index()
    {
        // //authUser()->can(Module::CONTACTS_VIEW);
        $facilities = Facility::filter(request()->all())
            ->with(['projects'])
            ->orderBy(request('orderBy', 'name'), request('orderType', 'ASC'))->get();
        return sysView('facilities.index', compact('facilities'));
    }

    public function store(Request $request)
    {
        if(!canAdd()){
            abort(404);
        }
        $this->validate($request, [
            'name' => 'required',
        ]);
        $facility = Facility::create($request->all());
        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Saved']),
            'redirect' => sysRoute('facilities.live-edit', encryptIt($facility->id))
        ]);
    }

    public function show($id)
    {
        
        $facility = Facility::findOrFail(decryptIt($id));
        return sysView('facilities.show', compact('facility'));
    }

    public function edit($id)
    {
        // //authUser()->can(Module::FACILITIES_EDIT);
        $facility = Facility::findOrFail(decryptIt($id));
        return sysView('facilities.partials.edit-modal', [
            'model' => $facility
        ]);
    }

    public function update(Request $request, $id)
    {
        if(!canAdd()){
            abort(404);
        }
        $data = $this->validate($request, [
            // 'name' => 'required',
            'name' => 'required',
            'owner' => 'required',
            'location' => 'required',
            'is_key_account' => 'required|boolean'
        ]);
        // dd($data);
        $facility = Facility::findOrFail(decryptIt($id));
        $facility->fill($data);
        $facility->save();


        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Saved']),
            'closeModal' => '.footerModal',
            'redirect' => sysRoute('facilities.index')
        ]);
    }

    public function destroy($id)
    {
        if(!canDelete()){
            abort(404);
        }
        // //authUser()->can(Module::FACILITIES_DELETE);
        $facility = Facility::findOrFail(decryptIt($id));
        if ($facility->selfDestruct()) {
            return ajaxSuccess([]);
        }
        abort(500, 'INVALID');
    }


    public function bulkAction(Request $request)
    {
        if(!canBulkDelete()){
            abort(404);
        }
        if (!in_array($request->action, ['deleteall']) && (!$request->ids || !count($request->ids))) {
            throw new ApplicationException("Please select atleast one item.");
        }
        switch ($request->action) {
            case 'delete':
                Facility::whereIn('id', $request->ids)
                    ->whereDoesntHave('projects')
                    ->delete();
                break;
        }
        return redirect()->back()->with(['success' => 'Action Complete']);
    }

    public function deleteTags()
    {
        if(!canBulkDelete()){
            abort(404);
        }
        // dd(Relation::morphMap());
        // Tag::whereNotIn('id', DB::table('taggables')->selectRaw('tag_id')
        // ->where('taggable_type', Contact::class)
        // ->groupBy('tag_id')
        //     ->pluck('tag_id')->toArray())->delete();

        Tag::whereDoesntHave('contacts')->delete();
        return redirect()->back()->with(['success' => "Tags Removed"]);
    }
}
