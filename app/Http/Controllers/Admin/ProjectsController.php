<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProjectExport;
use App\Module;
use App\Models\Tag;
use App\Models\Contact;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Imports\ProjectsImport;
use App\Models\Message;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Support\Exceptions\ApplicationException;

class ProjectsController extends Controller
{
    public function __construct()
    {
        /*
        $this->middleware('auth.onlyAdmin', [
            'except' => ['index', 'show']
        ]);
        */
    }
    public function index()
    {
        // //authUser()->can(Module::CONTACTS_VIEW);
        if (request()->ajax()) {
            $projects = Project::filter(request()->all())
                ->with([])
                ->forMe()
                ->orderBy(request('orderBy', 'pn'), request('orderType', 'DESC'))->paginate(request('per_page', 10));
            return ajaxSuccess([
                'data' => sysView('projects.partials.list', compact('projects'))->render(),
                'pagination' => view('components.pagination', [
                    'data' => $projects,
                    'perPage' => request('per_page', 10),
                    'perPageForm' => '#project-filter-form'
                ])->render()
            ]);
        }
        return sysView('projects.index');
    }

    public function create()
    {
        if (!canAdd()) {
            abort(404);
        }
        return sysView('projects.create');
    }

    public function store(Request $request)
    {
        if (!canAdd()) {
            abort(404);
        }
        $this->validate($request, [
            'name' => 'required',
        ]);
        $request->merge(['probability' => 40]);
        $project = Project::create($request->all());
        $project->updateJsonField([
            'timezone' => $request->timezone_offset
        ], 'metadata');
        return ajaxSuccess([
            'notification' => ReturnNotification(['success' => 'Saved']),
            'redirect' => sysRoute('projects.live-edit', encryptIt($project->id))
        ]);
    }

    public function show($id)
    {
        // //authUser()->can(Module::PROJECTS_VIEW);
        $project = Project::findOrFail(decryptIt($id));
        $previous = Project::where('pn', '<', $project->pn)
            ->orderBy('pn', 'desc')
            ->first();

        $next = Project::where('pn', '>', $project->pn)
            ->orderBy('pn', 'asc')
            ->first();
        return sysView('projects.show', compact('project', 'next', 'previous'));
    }

    public function edit($id)
    {
        if (!canAdd()) {
            abort(404);
        }
        // //authUser()->can(Module::PROJECTS_EDIT);
        $project = Project::findOrFail(decryptIt($id));
        $previous = Project::where('pn', '<', $project->pn)
            ->orderBy('pn', 'desc')
            ->first();

        $next = Project::where('pn', '>', $project->pn)
            ->orderBy('pn', 'asc')
            ->first();
        return sysView('projects.edit', compact('project', 'next', 'previous'));
    }

    public function update(Request $request, $id)
    {
        if (!canAdd()) {
            abort(404);
        }
        // //authUser()->can(Module::PROJECTS_EDIT);
        $this->validate($request, [
            'type' => 'required',
            'city' => 'required',
            'state' => 'required',
            'contacts.*.name' => 'required',
            'contacts.*.email' => 'required',
            'contacts.*.phone' => 'required',
            'contacts.*.cell' => 'required',
        ]);

        $project = Project::findOrFail(decryptIt($id));
        $project->fill($request->except('contacts'));
        $project->save();

        DB::transaction(function () use ($request, $project) {
            $project->contacts()->delete();
            foreach ($request->post('contacts', []) as $k => $contact) {
                // dd($contact['tags']);
                $contactModel = Contact::create(array_merge($contact, [
                    'project_id' => $project->id,
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
            // 'redirect' => sysRoute('projects.index')
        ]);
    }

    public function destroy($id)
    {
        if (!canDelete()) {
            abort(404);
        }
        // //authUser()->can(Module::PROJECTS_DELETE);
        $project = Project::findOrFail(decryptIt($id));
        if ($project->selfDestruct()) {
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
            'file' => 'required|mimes:csv'
        ]);
        //authUser()->can(Module::PROJECTS_BULK);
        Excel::import(new ProjectsImport($request->timezone_offset), $request->file('file')->getPathname(), null, ExcelExcel::CSV);
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
                DB::table('project_clients')->whereIn('project_id', $request->ids)->delete();
                DB::table('project_estimators')->whereIn('project_id', $request->ids)->delete();
                DB::table('project_companies')->whereIn('project_id', $request->ids)->delete();
                Message::whereIn('project_id', $request->ids)->delete();
                Project::whereIn('id', $request->ids)->delete();
                break;

            case 'deleteall':
                DB::table('project_clients')->where('project_id', '>', 0)->delete();
                DB::table('project_estimators')->where('project_id', '>', 0)->delete();
                DB::table('project_companies')->where('project_id', '>', 0)->delete();
                Message::whereNotNull('project_id')->delete();
                Project::where('id', '>', 0)->delete();
                break;

            case 'edit':
                $projects = Project::whereIn('id', $request->ids)->get();
                return sysView('projects.bulk-edit', compact('projects'));
                break;
        }
        return redirect()->back()->with(['success' => 'Action Complete']);
    }


    public function export(Request $request)
    {
        if (!isManagement()) {
            abort(404);
        }

        return Excel::download(new ProjectExport($request->all()), 'projects-' . date("Y-m-d") . '.xlsx', ExcelExcel::XLSX);
    }
}
