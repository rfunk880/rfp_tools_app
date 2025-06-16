<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectExport implements FromQuery, WithMapping, WithHeadings
{
   

    public function map($project): array
    {
        return [
            @$project->pn,
            @$project->name,
            $project->subcontractor_bid_due ? toTimezoneDate($project->subcontractor_bid_due, 'm-d-Y g:i A', @$project->metadata['timezone']) : '',
            $project->salesPersons->pluck('name')->join(', '),
            $project->estimators->pluck('name')->join(', '),
            strip_tags(@\App\Models\Project::$poStatusLabel[$project->po_status]),
            toAppDate($project->est_start_date),
            toAppDate($project->est_end_date),
            money($project->final_estimate),
            (int) $project->duration,
            $project->is_key_account ? 'Yes' : 'No',
            @$project->facility->name,
            @$project->facility->owner,
            @$project->facility->location,
            strip_tags(@\App\Models\Project::$poStatusLabel[$project->po_status]) ?? 'N/A',
            strip_tags(@\App\Models\Project::$statusLabel[$project->status]),
            $project->created_at ? $project->created_at->format("Y-m-d g:i A") : ''
        ];
    }

    public function query()
    {
        return Project::query()->filter(request()->all());
    }


    public function headings(): array
    {
        return [
            'Project Number',
            'Project Name',
            'Bid Due Date',
            'Salespersons',
            'Estimators',
            'PO Status',
            'Est Start Date',
            'Est End Date',
            'Final Estimate',
            'Duration(days)',
            'Key Account',
            'Facility Name',
            'Facility Owner',
            'Facility Location',
            'Bid Status',
            'Project Status',
            'Created Date/Time'
        ];
    }
}
