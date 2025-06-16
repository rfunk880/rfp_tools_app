<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ChartService;
use OptimaIt\Transcript\SubtitleFactory;
use OptimaIt\Transcript\TranscriptFinder;
use OptimaIt\Transcript\TranscriptGenerator;

class PagesController extends Controller
{

    public function dashboard()
    {

        $optionLabel = match (request()->get('interval')) {
            'last_month' => 'Last Month',
            'this_month' => 'This Month',
            'last_3_months' => 'Last 3 Months',
            'last_6_months' => 'Last 6 Months',
            'last_year' => 'Last 12 Months',
            'custom' => 'From ' . request()->get('date_from') . ' to ' . request()->get('date_to'),

            default => 'Last 30 Days'
        };

        $wonProjects = Project::dashboardFilter(request()->merge([
            'date_col' => 'awarded_date'
        ]))
            ->with('facility')
            ->orderBy('awarded_date', 'ASC')
            ->get();

        $projects = Project::dashboardFilter(request()->merge([
            'date_col' => 'bid_due'
        ]))
            ->with('facility')
            ->whereNotIn('id', $wonProjects->pluck('id')->toArray())
            ->orderBy('bid_due', 'ASC')
            ->get();

        $projects = $projects->merge($wonProjects);

        $keyProjects = $projects->filter(function (Project $project) {
            return @$project->facility->is_key_account;
        });




        $won = $wonProjects->filter(function (Project $project) {
            return $project->isP0StatusAwarded() && $project->isWon();
        });

        $lost = $projects->filter(function (Project $project) {
            return !$project->isP0StatusAwarded();
        });



        return view('webpanel.dashboard', [
            'jsData' => [
                'chartData' => ChartService::FinalEstimateChart($projects),
                'wonCount' => $won->count(),
                'lostCount' => $lost->count(),
                'wonSum' => $won->sum('final_estimate'),
                'lostSum' => $lost->sum('final_estimate'),
                'totalSum' => $projects->sum('final_estimate'),
                'totalCount' => $projects->count(),
                'wonSumRatio' => $projects->sum('final_estimate') > 0 ? number_format(($won->sum('final_estimate') * 100) / ($projects->sum('final_estimate')), 2) : '0'
                // 'chartData' => ChartService::FinalEstimateChart($projects),
            ],
            'projects' => $projects,
            'keyProjects' => $keyProjects,
            'won' => $won,
            'lost' => $lost,
            'optionLabel' => $optionLabel
        ]);
    }
}
