<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Exports\ProjectExport;
use App\Imports\ProjectsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        if (!can('view-project')) {
            abort(404);
        }
        $projects = Project::with(['facility', 'estimators', 'salesPersons'])
            ->filter($request->all())
            ->forMe()
            ->latest()->paginate(20);
        return view('webpanel.projects.index', compact('projects'));
    }

    public function create()
    {
        if (!canAdd()) {
            abort(404);
        }
        return view('webpanel.projects.create');
    }

    public function store(Request $request)
    {
        if (!canAdd()) {
            abort(404);
        }
        $this->validate($request, [
            'name' => 'required',
            'facility_id' => 'required'
        ]);

        $project = Project::create($request->all());

        flash('Project created successfully!')->success();

        return redirect()->route('projects.live-edit', encryptIt($project->id));
    }


    public function show($id)
    {
        $project = Project::with(['facility', 'estimators', 'salesPersons'])->findOrFail(decryptIt($id));
        return view('webpanel.projects.show', compact('project'));
    }

    public function edit($id)
    {
        if (!canEdit()) {
            abort(404);
        }
        $project = Project::findOrFail(decryptIt($id));
        return view('webpanel.projects.edit', compact('project'));
    }

    public function liveEdit($id)
    {
        if (!canEdit()) {
            abort(404);
        }
        // $project = Project::findOrFail(decryptIt($id));
        return view('webpanel.projects.live-edit', compact('id'));
    }


    public function update(Request $request, $id)
    {
        if (!canEdit()) {
            abort(404);
        }
        $this->validate($request, [
            'name' => 'required',
            'facility_id' => 'required'
        ]);
        $project = Project::findOrFail(decryptIt($id));
        $project->update($request->all());

        $project->estimators()->detach();
        $project->estimators()->attach($request->estimators, [
            'type' => 'estimators'
        ]);

        $project->salesPersons()->detach();
        $project->salesPersons()->attach($request->sales_persons, [
            'type' => 'sales'
        ]);


        flash('Project updated successfully!')->success();

        return redirect()->back();
    }


    public function destroy($id)
    {
        if (!canDelete()) {
            abort(404);
        }
        $project = Project::findOrFail(decryptIt($id));
        $project->delete();
        flash('Project deleted successfully!')->success();
        return redirect()->route('projects.index');
    }

    public function export(Request $request)
    {
        if (!can('export-project')) {
            abort(404);
        }
        return Excel::download(new ProjectExport($request->all()), 'projects.xlsx');
    }

    public function import(Request $request)
    {
        if (!can('import-project')) {
            abort(404);
        }
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        Excel::import(new ProjectsImport, $request->file('file'));
        flash('Projects has been imported')->success();
        return redirect()->back();
    }

    public function bulkEdit(Request $request)
    {
        if (!can('bulk-edit-project')) {
            abort(404);
        }
        $this->validate($request, [
            'projects' => 'required'
        ]);
        $projects = Project::whereIn('id', $request->projects)->get();
        return view('webpanel.projects.bulk-edit', compact('projects'));
    }

    public function bulkUpdate(Request $request)
    {
        if (!can('bulk-edit-project')) {
            abort(404);
        }
        
        $this->validate($request, [
            'projects' => 'required|array',
            'status' => 'nullable',
            'po_status' => 'nullable',
            'awarded_date' => 'nullable|date_format:m-d-Y', // It's good practice to validate the date format
        ]);

        $updateData = [];

        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        if ($request->filled('po_status')) {
            $updateData['po_status'] = $request->po_status;
        }

        // Only add the awarded_date to the update array if it's actually provided.
        if ($request->filled('awarded_date')) {
            // The `toMysqlDate` helper will correctly format the date for the database.
            $updateData['awarded_date'] = toMysqlDate($request->awarded_date);
        }

        if (count($updateData) > 0) {
            Project::whereIn('id', $request->projects)->update($updateData);
            flash('Projects have been updated')->success();
        } else {
            flash('No changes were selected')->info();
        }


        return redirect()->route('projects.index');
    }

    public function bulkDelete(Request $request)
    {
        if (!can('bulk-delete-project')) {
            abort(404);
        }
        $this->validate($request, [
            'projects' => 'required'
        ]);
        foreach ($request->projects as $id) {
            $project = Project::find($id);
            if ($project) {
                $project->selfDestruct();
            }
        }
        flash('Projects have been deleted')->success();
        return redirect()->route('projects.index');
    }

    public function addCompany(Request $request, $id)
    {
        if (!canEdit()) {
            abort(404);
        }
        $project = Project::findOrFail(decryptIt($id));
        $this->validate($request, [
            'company_id' => 'required',
            'type' => 'required'
        ]);

        if ($request->type == 'client') {
            $project->clients()->attach($request->company_id);
        } else {
            $project->companies()->attach($request->company_id);
        }

        flash('Company added successfully')->success();

        return redirect()->back();
    }

    public function removeCompany(Request $request, $id, $company_id)
    {
        if (!canEdit()) {
            abort(404);
        }
        $project = Project::findOrFail(decryptIt($id));
        if ($request->type == 'client') {
            $project->clients()->detach($company_id);
        } else {
            $project->companies()->detach($company_id);
        }
        flash('Company removed successfully')->success();
        return redirect()->back();
    }
}
