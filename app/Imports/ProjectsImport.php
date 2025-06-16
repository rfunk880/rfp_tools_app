<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Project;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProjectsImport implements ToCollection, WithHeadingRow
{
    public function __construct(public $timezone = null)
    {
        
    }
    
    public $facilities = [];
    public $clients = [];
    public $estimators = [];

    public static $status = [
        'prospect' => Project::STATUS_PROSPECT,
        'bidding' => Project::STATUS_BIDDING,
        'no bid' => Project::STATUS_NO_BID,
        'pending' => Project::STATUS_PENDING,
        'won' => Project::STATUS_WON,
        'lost' => Project::STATUS_LOST,
        'budget' => Project::STATUS_BUDGET,
        'cancelled' => Project::STATUS_CANCELLED,
        'unknown' => Project::STATUS_UNKNOWN,
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $facility_id = $this->getFacilityId($row);
            $project = Project::create([
                'name'     => @$row['project_name'],
                'bid_due'    => toAppDate(@$row['bid_date']),
                'final_estimate' => stringToDecimal(@$row['final_estimate']),
                'facility_id' => $facility_id,
                'status' => @self::$status[strtolower(@$row['project_status'])] ?? 0
            ]);

            $clientId = $this->getClientid($row);

            if ($clientId) {
                $project->clients()->attach($clientId);
            }

            if ($estimatorId = $this->getEstimatorId($row)) {
                $project->estimators()->attach($estimatorId, [
                    'type' => 'estimators'
                ]);
            }
            if($this->timezone){
                $project->updateJsonField([
                    'timezone' => $this->timezone
                ], 'metadata');
            }
        }
    }

    public function getClientid($row)
    {
        $key = strtolower($row['client']);

        if (isset($this->clients[$key])) {
            return $this->clients[$key];
        }

        $client = Company::firstOrCreate([
            'name' => $row['client'],
        ]);

        $client->fill([
            'city' => @$row['city'],
            'state' => @$row['state'],
            'type' => Company::TYPE_SUBCONTRACTOR,
        ]);
        $client->save();
        $this->clients[$key] = $client->id;
        return $client->id;
    }

    public function getFacilityId($row)
    {
        if (@$row['facility_name'] == '') {
            return null;
        }
        $key = strtolower(@$row['facility_name']);
        if (isset($this->facilities[$key])) {
            return $this->facilities[$key];
        }
        $facility = Facility::firstOrCreate([
            'name' => $row['facility_name']
        ]);
        $this->facilities[$key] = $facility->id;
        return $facility->id;
    }

    public function getEstimatorId($row)
    {
        $key = strtolower($row['estimator']);
        if (isset($this->estimators[$key])) {
            return $this->estimators[$key];
        }
        $user = User::where([
            'name' => $row['estimator'],
        ])->first();

        if ($user) {
            $this->estimators[$key] = $user->id;
            return $user->id;
        }
        $this->estimators[$key] = null;
        return null;
    }
}
