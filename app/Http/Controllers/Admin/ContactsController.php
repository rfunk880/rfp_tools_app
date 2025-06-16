<?php
namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Contact;
use App\Models\Tag;
use App\Module;
use Support\Exceptions\ApplicationException;

class ContactsController extends Controller
{
    public function index()
    {
        // //authUser()->can(Module::CONTACTS_VIEW);
        if (request()->ajax()) {
            $contacts = Contact::filter(request()->all())
                ->with(['tags'])
                ->orderBy(request('orderBy', 'created_at'), request('orderType', 'DESC'))->paginate(10);
            return ajaxSuccess([
                'data' => sysView('contacts.partials.list', compact('contacts'))->render(),
                'pagination' => view('components.pagination', ['data' => $contacts])->render()
            ]);
        }
        return sysView('contacts.index');
    }

    public function create()
    {
        if(!canAdd()){
            abort(404);
        }
        return sysView('contacts.create');
    }

    public function store(Request $request)
    {
        if(!canAdd()){
            abort(404);
        }

        $request->merge(['url' => 'https://' . $request->url]);
        $this->validate($request, [
            'title' => 'required',
            'location' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'date|after:start_date'
        ]);

        $contact = Contact::create($request->all());
        if ($request->tags) {
            $contact->tags()->detach();
            foreach ($request->tags as $k => $tag) {

                $contact->tags()->save(Tag::firstOrCreate(['name' => $tag]));
            }
        }
        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Saved']),
            'redirect' => sysRoute('contacts.edit', encryptIt($contact->id))
        ]);
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        if(!canAdd()){
            abort(404);
        }
        $contact = Contact::findOrFail(decryptIt($id));
        return sysView('contacts.edit', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        if(!canAdd()){
            abort(404);
        }

        $request->merge(['url' => 'https://' . $request->url]);
        $this->validate($request, [
            'title' => 'required',
            'location' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'date|after:start_date'
        ]);
        $contact = Contact::findOrFail(decryptIt($id));
        $contact->fill($request->all());
        $contact->save();
        if ($request->tags) {
            $contact->tags()->detach();
            foreach ($request->tags as $k => $tag) 
            {
                $contact->tags()->save(Tag::firstOrCreate(['name' => $tag]));
            }
        }
        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Saved']),
            // 'redirect' => sysRoute('contacts.index')
        ]);
    }

    public function destroy($id)
    {
        if(!canDelete()){
            abort(404);
        }
        $contact = Contact::findOrFail(decryptIt($id));
        if ($contact->selfDestruct()) {
            return ajaxSuccess([]);
        }
        abort(500, 'INVALID');
    }
}