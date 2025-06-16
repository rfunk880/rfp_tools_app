<?php
namespace App\Http\Controllers\Admin;

use App\Exports\ContactExport;
use App\Module;
use App\Models\Tag;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Imports\ContactsImport;
use App\Models\Project;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Support\Exceptions\ApplicationException;

class CompaniesController extends Controller
{
    public function index()
    {

        if (request()->ajax()) {
            $companies = Company::filter(request()->all())
                ->with(['primaryContact', 'contacts'])
                ->orderBy(request('orderBy', 'created_at'), request('orderType', 'DESC'))->paginate(request('per_page', 10));
            return ajaxSuccess([
                'data' => sysView('companies.partials.list', compact('companies'))->render(),
                'pagination' => view('components.pagination', [
                    'data' => $companies,
                    'perPage' => request('per_page', 10),
                    'perPageForm' => '#contact-filter-form'
                ])->render()
            ]);
        }
        return sysView('companies.index');
    }

    public function create()
    {
        if (!canAdd()) {
            abort(404);
        }
        return sysView('companies.create');
    }

    public function store(Request $request)
    {
        if (!canAdd()) {
            abort(404);
        }
        $this->validate($request, [
            'type' => 'required',
            'city' => 'required',
            'state' => 'required',
            'contacts.*.name' => 'required',
            'contacts.*.email' => 'required'
        ]);

        $company = Company::create($request->except('contacts'));
        foreach ($request->post('contacts', []) as $k => $contact) {
            $contactModel = Contact::create(array_merge($contact, ['company_id' => $company->id]));
            if (isset($contact['tags']) && is_array($contact['tags'])) {
                foreach ($contact['tags'] as $tag) {
                    $contactModel->tags()->save(Tag::firstOrCreate([
                        'name' => $tag
                    ]));
                }
            }
        }
        if ($request->project_id) {
            try {
                $project = Project::findOrFail(decryptIt($request->project_id));
                if ($request->get('context', 'clients') == 'clients') {
                    $project->clients()->attach($company->id);
                } else {
                    $project->companies()->attach($company->id);
                }
                return ajaxSuccess([
                    'notification' => ReturnNotification(['success' => 'Saved']),
                    'redirect' => sysRoute('projects.live-edit', $request->project_id)
                ]);
            } catch (\Exception $e) {
            }
        }
        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Saved']),
            'redirect' => sysRoute('companies.index')
        ]);
    }

    public function show($id)
    {
        $company = Company::findOrFail(decryptIt($id));
        return sysView('companies.show', compact('company'));
    }

    public function edit($id)
    {
        if (!canAdd()) {
            abort(404);
        }
        $company = Company::findOrFail(decryptIt($id));
        $jsData = [
            'contacts' => $company->contacts()->with('tags')->get()->map(function (Contact $contact) {
                return [
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                    'cell' => $contact->cell,
                    'title' => $contact->title,
                    'location' => $contact->location,
                    'notes' => $contact->notes,
                    'is_primary' => (bool) $contact->is_primary,
                    'tags' => $contact->tags->pluck('name')->toArray()
                ];
            })->toArray()
        ];
        return sysView('companies.edit', compact('company', 'jsData'));
    }

    public function update(Request $request, $id)
    {
        if (!canAdd()) {
            abort(404);
        }
        $this->validate($request, [
            'type' => 'required',
            'city' => 'required',
            'state' => 'required',
            'contacts.*.name' => 'required',
            'contacts.*.email' => 'required',
        ]);

        $company = Company::findOrFail(decryptIt($id));
        $company->fill($request->except('contacts'));
        $company->save();

        DB::transaction(function () use ($request, $company) {
            $company->contacts()->delete();
            foreach ($request->post('contacts', []) as $k => $contact) {
                $contactModel = Contact::create(array_merge($contact, [
                    'company_id' => $company->id,
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
            // 'redirect' => sysRoute('companies.index')
        ]);
    }

    public function destroy($id)
    {
        if (!canDelete()) {
            abort(404);
        }
        $company = Company::findOrFail(decryptIt($id));
        if ($company->selfDestruct()) {
            return ajaxSuccess([]);
        }
        abort(500, 'INVALID');
    }

    public function import(Request $request)
    {
        if (!canAdd()) {
            abort(404);
        }
        $this->validate($request, [
            'file' => 'required|mimes:xlsx'
        ]);
        Excel::import(new ContactsImport, $request->file('file')->getPathname(), null, ExcelExcel::XLSX);
        return redirect()->back()->with('success', 'Imported Successfully');
    }

    public function bulkAction(Request $request)
    {
        if (!canBulkDelete()) {
            abort(404);
        }
        if (!in_array($request->action, ['deleteall']) && (!$request->ids || !count($request->ids))) {
            throw new ApplicationException("Please select atleast one item.");
        }
        switch ($request->action) {
            case 'delete':
                Company::whereIn('id', $request->ids)->delete();
                Contact::whereIn('company_id', $request->ids)->delete();
                break;

            case 'deleteall':
                Company::where('id', '>', 0)->delete();
                Contact::where('company_id', '>', 0)->delete();
                break;
        }
        return redirect()->back()->with(['success' => 'Action Complete']);
    }


    public function export(Request $request)
    {
        if (!isManagement()) {
            abort(404);
        }
        return Excel::download(new ContactExport, 'Contacts-' . date("Y-m-d") . '.xlsx', ExcelExcel::XLSX);
    }
}
