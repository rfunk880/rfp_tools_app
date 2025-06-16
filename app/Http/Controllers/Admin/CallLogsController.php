<?php
namespace App\Http\Controllers\Admin;

use App\Events\CalllogGenerated;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Calllog;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Tag;
use App\Module;
use Illuminate\Support\Facades\DB;
use Support\Exceptions\ApplicationException;

class CallLogsController extends Controller
{
    public function index()
    {
        //authUser()->can(Module::CONTACTS_VIEW);
        if (request()->ajax()) {
            $calllogs = Calllog::filter(request()->all())
                ->with(['project', 'recepients', 'media'])
                ->orderBy(request('orderBy', 'created_at'), request('orderType', 'DESC'))->paginate(10);
            return ajaxSuccess([
                'data' => sysView('calllogs.partials.list', compact('calllogs'))->render(),
                'pagination' => view('components.pagination', ['data' => $calllogs])->render()
            ]);
        }
        return sysView('calllogs.index');
    }

    public function create()
    {
        if(!canAdd()){
            abort(404);
        }
        return sysView('calllogs.create', [
            'redirect_to_project' => request('redirect_to_project', 0)
        ]);
    }

    public function store(Request $request)
    {
        if(!canAdd()){
            abort(404);
        }
        $this->validate($request, [
            'subject' => 'required',
            'contact_id' => 'required',
            'content' => 'required',
            // 'type' => 'required'
        ]);

        $request->merge(['subject' => $request->subject_prefix . ':' . $request->subject]);
        // dd($request->all());

        $calllog = Calllog::create($request->all());
        DB::transaction(function () use ($calllog, $request) {
            if ($request->get('contact_id')) {
                $calllog->recepients()->attach($request->get('contact_id'));
            }
            
        });
        if ($request->file('files')) 
        {
            foreach ($request->file('files') as $file) {
                $calllog->addMedia($file->getPathName())
                    ->usingName($file->getClientOriginalName())
                    ->toMediaCollection('attachments');
            }
        }

        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Saved']),
            'redirect' => $request->redirect_to_project && $request->project_id ? sysRoute('projects.show', encryptIt($request->project_id)) : sysRoute('calllogs.index')
        ]);
    }

    public function show($id)
    {
        //authUser()->can(Module::CALLLOGS_VIEW);
        $calllog = Calllog::findOrFail(decryptIt($id));
        return sysView('calllogs.show', compact('calllog'));
    }

    public function edit($id)
    {
        if(!canAdd()){
            abort(404);
        }
        $calllog = Calllog::findOrFail(decryptIt($id));
        return sysView('calllogs.edit', compact('calllog'));
    }

    public function update(Request $request, $id)
    {
        if(!canAdd()){
            abort(404);
        }
        $this->validate($request, [
            // 'name' => 'required',
            'type' => 'required',
            'city' => 'required',
            'state' => 'required',
            'contacts.*.name' => 'required',
            'contacts.*.email' => 'required',
            'contacts.*.phone' => 'required',
            'contacts.*.cell' => 'required',
        ]);

        $calllog = Calllog::findOrFail(decryptIt($id));
        $calllog->fill($request->except('contacts'));
        $calllog->save();

        DB::transaction(function () use ($request, $calllog) {
            $calllog->contacts()->delete();
            foreach ($request->post('contacts', []) as $k => $contact) {
                // dd($contact['tags']);
                $contactModel = Contact::create(array_merge($contact, [
                    'calllog_id' => $calllog->id,
                    'is_primary' => @$contact['is_primary'] == 1 ? 1 : 0
                ]));
                if (isset($contact['tags']) && is_array($contact['tags'])) {
                    foreach ($contact['tags'] as $tag) {
                        // dd($tag);
                        $contactModel->tags()->save(Tag::firstOrCreate([
                            'name' => $tag
                        ]));
                    }
                }
            }
        });
        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Saved']),
            // 'redirect' => sysRoute('calllogs.index')
        ]);
    }

    public function destroy($id)
    {
        if(!canDelete()){
            abort(404);
        }
        $calllog = Calllog::findOrFail(decryptIt($id));
        if ($calllog->selfDestruct()) {
            return ajaxSuccess([]);
        }
        abort(500, 'INVALID');
    }

    public function createForProject($id)
    {
        $project = Project::findOrFail(decryptIt($id));
        return sysView('calllogs.create', [
            'project' => $project,
            'redirect_to_project' => request('redirect_to_project', 0)
        ]);
    }

    public function bulkAction(Request $request)
    {
        if(!canBulkDelete()){
            abort(404);
        }
        $this->validate($request, [
            'ids' => 'required'
        ]);
        switch ($request->post('action')) {
            default:
                Calllog::whereIn('id', $request->post('ids'))->delete();
                break;
        }
        return redirect()->back()->with(['success' => 'Bulk Action Completed']);
    }
}
