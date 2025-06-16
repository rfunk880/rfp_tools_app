<?php
namespace App\Http\Controllers\Admin;

use App\Events\MessageGenerated;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Message;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Tag;
use App\Module;
use Illuminate\Support\Facades\DB;
use Support\Exceptions\ApplicationException;

class MessagesController extends Controller
{
    public function index()
    {
        // //authUser()->can(Module::CONTACTS_VIEW);
        if (request()->ajax()) {
            $messages = Message::filter(request()->all())
                ->with(['project', 'recepients', 'media'])
                ->orderBy(request('orderBy', 'created_at'), request('orderType', 'DESC'))->paginate(10);
            return ajaxSuccess([
                'data' => sysView('messages.partials.list', compact('messages'))->render(),
                'pagination' => view('components.pagination', ['data' => $messages])->render()
            ]);
        }
        return sysView('messages.index');
    }

    public function create()
    {
        if(!canAdd()){
            abort(404);
        }
        return sysView('messages.create', [
            'redirect_to_project' => request('redirect_to_project', 0)
        ]);
    }

    public function store(Request $request)
    {
        if(!canAdd()){
            abort(404);
        }
        // $request->merge(['additional_emails' => explode(",", $request->get('send_to_emails', ''))]);
        // dd($request->all());
        $this->validate($request, [
            'subject' => 'required',
            'contact_id' => 'required_unless:send_to_all,1',
            'send_to_emails.*' => 'email',
            'content' => 'required',
            'project_id' => 'required',
            'type' => 'required'
        ], [
            'send_to_emails.*.email' => 'All additional emails must be valid emails'
        ]);

        $request->merge(['subject' => $request->subject_prefix . ':' . $request->subject]);

        // dd($request->all());
        // if($request->get('send_to_all', null) == '1'){
        //     $contactIds =  Project::findOrFail($request->project_id)->getAllContactIds();

            
        //     $request->merge([
        //         'contact_id' => $contactIds
        //     ]);
        // }

        // dd($request->all());

        $message = Message::create($request->all());
        DB::transaction(function () use ($message, $request) {
            if ($request->get('contact_id')) {
                $message->recepients()->attach($request->get('contact_id'));
            }

            if($request->get('send_to_emails')){
                $message->updateJsonField([
                    'extra_emails' => $request->get('send_to_emails')
                ], 'metadata');
            }
            // $userIds = array_merge($request->get('user_id', []), []/* [authUser()->id] */);
            // $message->ccUsers()->attach($userIds);
        });
        if ($request->file('files')) 
        {
            foreach ($request->file('files') as $file) {
                $message->addMedia($file->getPathName())
                    ->usingName($file->getClientOriginalName())
                    ->toMediaCollection('attachments');
            }
        }
        event(new MessageGenerated($message));
        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Saved']),
            'redirect' => $request->redirect_to_project && $request->project_id ? sysRoute('projects.show', encryptIt($request->project_id)) : sysRoute('messages.index')
        ]);
    }

    public function show($id)
    {
        //authUser()->can(Module::MESSAGES_VIEW);
        $message = Message::findOrFail(decryptIt($id));
        return sysView('messages.show', compact('message'));
    }

    public function edit($id)
    {
     
        if(!canAdd()){
            abort(404);
        }
        $message = Message::findOrFail(decryptIt($id));
        return sysView('messages.edit', compact('message'));
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

        $message = Message::findOrFail(decryptIt($id));
        $message->fill($request->except('contacts'));
        $message->save();

        DB::transaction(function () use ($request, $message) {
            $message->contacts()->delete();
            foreach ($request->post('contacts', []) as $k => $contact) {
                // dd($contact['tags']);
                $contactModel = Contact::create(array_merge($contact, [
                    'message_id' => $message->id,
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
            // 'redirect' => sysRoute('messages.index')
        ]);
    }

    public function destroy($id)
    {
        if(!canDelete()){
            abort(404);
        }
        $message = Message::findOrFail(decryptIt($id));
        if ($message->selfDestruct()) {
            return ajaxSuccess([]);
        }
        abort(500, 'INVALID');
    }

    public function createForProject($id)
    {
        if(!canAdd()){
            abort(404);
        }
        $project = Project::findOrFail(decryptIt($id));
        return sysView('messages.create', [
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
                Message::whereIn('id', $request->post('ids'))->delete();
                break;
        }
        return redirect()->back()->with(['success' => 'Bulk Action Completed']);
    }
}
