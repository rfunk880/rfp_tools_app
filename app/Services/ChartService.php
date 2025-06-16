<?php

namespace App\Services;

class ChartService
{

    public static function FinalEstimateChart($projects)
    {
        // Project::amongStatus([Project::STATUS_WON, Project::STATUS_LOST])
        //     ->dashboardFilter(request()->all())
        //     ->get();

        $data = [
            'labels' => [],
            'count' => [],
            'total' => []
        ];

        $groups = $projects->groupBy(function ($item) {
            $date = new \DateTime(toAppDate($item->bid_due, 'Y-m-d'));
            $date = $date->modify('sunday this week');
            return $date->format('Y-m-d');
        });

        // dd($groups);

        foreach ($groups as $label => $collection) {
            
            $data['labels'][] = $label;
            $data['count'][] = $collection->count();
            $data['total'][] = round($collection->sum('final_estimate'),2);
        }
        return $data;
    }
}
