<?php
namespace App\Http\Controllers\Admin;

use App\Module;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Project;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Support\Exceptions\ApplicationException;
use Support\Services\PdfExport\PdfExportService;

class ReportsController extends Controller
{
    public function bidReport()
    {
        //authUser()->can(Module::REPORTS_VIEW);
        if (request()->ajax() || request('pdf')) {
            $builder = Project::filter(request()->merge(['status' => 'all']))
                ->forBidReport()
                ->amongStatus([Project::STATUS_BIDDING, Project::STATUS_PROSPECT, Project::STATUS_PENDING, Project::STATUS_WON])
                ->amongPOStatus([Project::STATUS_PO_PENDING, null, 0])
                ->with(['estimators', 'clients']);

            if (request('pdf')) {
                return $this->downloadReport($builder
                    ->orderBy('bid_due', 'ASC')
                    ->get(), 'bid');
            }
            $projects = $builder
                ->orderBy(request('orderBy', 'created_at'), request('orderType', 'DESC'))
                ->paginate(10);
            return ajaxSuccess([
                'data' => sysView('reports.partials.list-bid', compact('projects'))->render(),
                'pagination' => view('components.pagination', ['data' => $projects])->render()
            ]);
        }
        return sysView('reports.bid');
    }
    
    public function meetingsReport()
    {
        //authUser()->can(Module::REPORTS_VIEW);
        if (request()->ajax() || request('pdf')) {
            $builder = Project::forMeetingReport(request()->all())
                ->with(['estimators', 'clients']);
            if (request('pdf')) {
                return $this->downloadReport($builder
                    ->orderBy('bid_due', 'ASC')
                    ->get(), 'bid');
            }
            $projects = $builder
                ->orderBy(request('orderBy', 'bid_due'), request('orderType', 'DESC'))
                ->paginate(10);
            return ajaxSuccess([
                'data' => sysView('reports.partials.list-meeting', compact('projects'))->render(),
                'pagination' => view('components.pagination', ['data' => $projects])->render()
            ]);
        }
        return sysView('reports.meeting');
    }

    public function facilityReport()
    {
        //authUser()->can(Module::REPORTS_VIEW);
        if (request()->ajax() || request('pdf')) {

        $builder = Project::facilityReportFilter(request()->all())->selectRaw("
        facility_id, 
         SUM(CASE WHEN status >= 3 THEN final_estimate ELSE 0 END) as total_submitted,
         SUM(CASE WHEN status = 4 THEN final_estimate ELSE 0 END) as total_won
        ")
        ->leftJoin('facilities', 'facilities.id', '=', 'projects.facility_id')
        ->groupBy('facility_id')->with(['facility'])->orderBy(request('orderBy', 'total_submitted'), request('orderType', 'DESC'));
            if (request('pdf')) {
                return $this->downloadReport($builder->get(), 'facility');
            }
            $projects = $builder->get();

            return ajaxSuccess([
                'data' => sysView('reports.partials.list-facility', compact('projects'))->render(),
                // 'pagination' => view('components.pagination', ['data' => $projects])->render()
            ]);
        }
        return sysView('reports.facility');
    }

    public function salesReport()
    {
        //authUser()->can(Module::REPORTS_VIEW);
        if (request()->ajax() || request('pdf')) {

            $builder = Project::forSalesReport(request()->all())->selectRaw("
        projects.*
        ")/* ->from(DB::raw("projects 
        LEFT JOIN project_estimators PE
  ON projects.id = PE.project_id
  AND PE.type = 'sales'
JOIN users U
  ON PE.user_id = U.id
        ")) */
                // ->leftJoin('project_estimators', 'projects.id', '=', 'project_estimators.project_id')
                // ->leftJoin('users', 'users.id', '=', 'project_estimators.user_id')
                // ->where('project_estimators.type', 'sales')
                // ->groupBy('U.id')
                // ->groupBy('U.name')
                // ->where('status', Project::STATUS_WON)
                
                ->leftJoin('facilities', 'facilities.id', '=', 'projects.facility_id')
                ->with(['facility', 'salesPersons'])->orderBy(request('orderBy', 'final_estimate'), request('orderType', 'DESC'));
            if (request('pdf')) {
                return $this->downloadReport($builder->get(), 'sales');
            }
            $total = $builder->sum('final_estimate');
            $projects = $builder->paginate(50);


            return ajaxSuccess([
                'data' => sysView('reports.partials.list-sales', compact('projects', 'total'))->render(),
                'pagination' => view('components.pagination', ['data' => $projects])->render()
            ]);
        }
        return sysView('reports.sales');
    }


    public function coverageReport()
    {

        $project = Project::where('pn', request('pn'))->first();
        $tags = [];
        $noTags = [];
        if ($project) {
            $contacts = Contact::whereIn('id', $project->getAllContactIds())->orderBy('name', "ASC")->with('tags', 'company')->get()->map(function ($item) {
                $item->tags = $item->tags->pluck('name')->toArray();
                return $item;
            });
            foreach ($contacts as $contact) {
                if (count($contact['tags'])) {
                    foreach ($contact['tags'] as $tag) {
                        if (!isset($tags[$tag])) {
                            $tags[$tag] = [];
                        }

                        $tags[$tag][] = $contact;
                    }
                } else {
                    $noTags[] = $contact;
                }
            }
            ksort($tags);
            $tags = array_merge($tags, ['No Tag' => $noTags]);
            // dd($tags);
        }
        return sysView('reports.coverage', compact('project', 'tags'));
    }

    public function downloadReport($collection, $view = 'bid')
    {
        config([
            'dompdf.options.enable_php' => true
        ]);

        $name = $view . ' Report - ' . date("m/d/Y") . '.pdf';
        return Pdf::loadView('pdf.reports.' . $view, ['rows' => $collection])
            ->setPaper('a4', 'landscape')
            ->download($name);
    }
}
