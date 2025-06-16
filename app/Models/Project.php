<?php

namespace App\Models;

use DateTime;
use App\Traits\LastUpdaterTrait;
use Support\Traits\HasJsonFields;
use App\Traits\ProjectStatusTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    use LastUpdaterTrait;
    use ProjectStatusTrait;
    use HasJsonFields;

    const STATUS_PROSPECT = 0;
    const STATUS_BIDDING = 1;
    const STATUS_NO_BID = 2;
    const STATUS_PENDING = 3;
    const STATUS_WON = 4;
    const STATUS_LOST = 5;
    const STATUS_BUDGET = 6;
    const STATUS_CANCELLED = 7;
    const STATUS_UNKNOWN = 8;

    const STATUS_PO_PENDING = 1;
    const STATUS_PO_AWARDED = 2;

    protected $table = 'projects';

    protected $casts = [
        // 'bid_due' => 'date:Y-m-d',
        // 'site_visit' => 'date:Y-m-d'
        'duration' => 'int',
        'sync_data' => 'json',
        'metadata' => 'json',
        // 'awarded_date' => 'date'
    ];

    protected $fillable = [
        'pn',
        'name',
        'facility_id',
        'site_visit',
        'bid_due',
        'awarded_date',
        // 'is_key_account',
        'subcontractor_bid_due',
        'bid_document',
        'status',
        'final_estimate',
        'po_status',
        'est_start_date',
        'est_end_date',
        'duration',
        'public_notes',
        'internal_notes',
        'sales_person_id',
        'probability',
        'awarded_date',
    ];


    public static $statusLabel = [
        self::STATUS_PROSPECT => '<span class="badge badge-pill bg-secondary">PROSPECT</span>',
        self::STATUS_BIDDING => '<span class="badge badge-pill bg-info">BIDDING</span>',
        self::STATUS_NO_BID => '<span class="badge badge-pill bg-warning">NO BID</span>',
        self::STATUS_PENDING => '<span class="badge badge-pill bg-dark">PENDING</span>',
        self::STATUS_WON => '<span class="badge badge-pill bg-success">WON</span>',
        self::STATUS_LOST => '<span class="badge badge-pill bg-warning">LOST</span>',
        self::STATUS_BUDGET => '<span class="badge badge-pill bg-primary">BUDGET</span>',
        self::STATUS_UNKNOWN => '<span class="badge badge-pill bg-danger">UNKNOWN</span>',
        self::STATUS_CANCELLED => '<span class="badge badge-pill bg-danger">CANCELLED</span>',
    ];

    public static $poStatusLabel = [
        self::STATUS_PO_PENDING => '<span class="badge badge-pill bg-secondary">PENDING</span>',
        self::STATUS_PO_AWARDED => '<span class="badge badge-pill bg-info">AWARDED</span>',

    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach (['bid_due', 'site_visit', 'est_start_date', 'est_end_date', 'subcontractor_bid_due'] as $dateCol) {
                if ($val = $model->{$dateCol}) {
                    $model->{$dateCol} = toMysqlDate($val);
                }
                try {
                } catch (\Exception $e) {
                    // dd($dateCol);
                }
            }
        });

        static::created(function ($model) {
            $model->pn = 1000 + $model->id;
            $model->save();
        });
    }

    public function scopeFilter($q, $params = [])
    {
        return $q->where(function ($q) use ($params) {
            if (@$params['keyword'] != '') {
                $keyword = urldecode($params['keyword']);
                $q->where(function ($q) use ($keyword) {
                    $q
                        ->orWhere('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('pn', 'LIKE', '%' . $keyword . '%');
                    // // ->orWhere('city', 'LIKE', '%' . $keyword . '%')
                    // ->orWhere('state', $keyword)
                    // ->orWhere('phone', $keyword)
                    $q->orWhereHas('facility', function ($q) use ($keyword) {
                        return $q->where('name', 'LIKE', '%' . $keyword . '%');
                    });;
                });
            }

            if (@$params['status'] != 'all') {
                if (@$params['status'] != '') {
                    $q->where('status', $params['status']);
                } else {
                    $q->whereIn('status', [Project::STATUS_BIDDING, Project::STATUS_PENDING, Project::STATUS_PROSPECT]);
                }
            }
        });
    }
    public function scopeForMeetingReport($q, $params = [])
    {
        return $q->where(function ($q) use ($params) {
            if (@$params['keyword'] != '') {
                $keyword = urldecode($params['keyword']);
                $q->where(function ($q) use ($keyword) {
                    $q
                        ->orWhere('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('pn', 'LIKE', '%' . $keyword . '%');
                    // // ->orWhere('city', 'LIKE', '%' . $keyword . '%')
                    // ->orWhere('state', $keyword)
                    // ->orWhere('phone', $keyword)
                    $q->orWhereHas('facility', function ($q) use ($keyword) {
                        return $q->where('name', 'LIKE', '%' . $keyword . '%');
                    });;
                });
            }

            if (@$params['status'] && is_array($params['status'])) {
                $q->where(function ($q) use ($params) {
                    if (in_array(Project::STATUS_WON, $params['status'])) {
                        $q->orWhere(function ($q) {
                            $q->where('status', Project::STATUS_WON)
                                ->whereIn('po_status', [Project::STATUS_PO_PENDING, 0]);
                        });
                    } else {
                    $q->whereIn('status', $params['status']);

                    }
                });
            }
        });
    }


    public function scopeFacilityReportFilter($q, $params = [])
    {
        return $q->where(function ($q) use ($params) {
            if (@$params['facility_id'] != '') {
                $q->where('facility_id', '=', $params['facility_id']);
            }
            if (@$params['date_from'] != '') {
                // dd(DateTime::createFromFormat("%m-%d-%Y", $params['date_from']));
                $q->whereDate('bid_due', '>=', DateTime::createFromFormat("m-d-Y", $params['date_from'])->format("Y-m-d"));
            }
            if (@$params['date_to'] != '') {
                $q->whereDate('bid_due', '<=', DateTime::createFromFormat("m-d-Y", $params['date_to'])->format("Y-m-d"));
            }

            if (@$params['is_key_account'] != '') {
                $q->where('facilities.is_key_account', 1);
            }

            if (@$params['status'] != '') {
                if (is_array(@$params['status'])) {
                    $q->whereIn('status', $params['status']);
                } else {
                    $q->where('status', $params['status']);
                }
            }
        });
    }

    public function scopeDashboardFilter($q, $params = [])
    {
        return $q->where(function ($q) use ($params) {
            $dateCol = @$params['date_col'] ?? 'bid_due';
            switch (@$params['interval']) {
                case 'last_3_months':
                    $q->whereRaw("DATE($dateCol) >= CURRENT_DATE - INTERVAL 3 MONTH");
                    break;

                case 'last_6_months':
                    $q->whereRaw("DATE($dateCol) >= CURRENT_DATE - INTERVAL 6 MONTH");
                    break;

                case 'this_month':
                    $q->whereRaw("MONTH($dateCol) = MONTH(CURRENT_DATE) AND YEAR($dateCol) = YEAR(CURRENT_DATE)");
                    break;
                case 'last_week':
                    $q->whereRaw("DATE($dateCol) >= CURRENT_DATE - INTERVAL 1 WEEK");
                    break;

                case 'last_year':
                    $q->whereRaw("DATE($dateCol) >= CURRENT_DATE - INTERVAL 1 YEAR");
                    break;

                case 'custom':
                    if (@$params['date_from'] != '') {
                        // dd(DateTime::createFromFormat("%m-%d-%Y", $params['date_from']));
                        $q->whereDate($dateCol, '>=', DateTime::createFromFormat("m-d-Y", $params['date_from'])->format("Y-m-d"));
                    }
                    if (@$params['date_to'] != '') {
                        $q->whereDate($dateCol, '<=', DateTime::createFromFormat("m-d-Y", $params['date_to'])->format("Y-m-d"));
                    }
                    break;
                default:
                    $q->whereRaw("DATE($dateCol) >= CURRENT_DATE - INTERVAL 1 MONTH");
                    break;
            }
        });
    }


    public function scopeForBidReport($q)
    {
        // return $q->amongStatus([Project::STATUS_BIDDING, Project::STATUS_PROSPECT, Project::STATUS_PENDING, Project::STATUS_WON])
        //     ->amongPOStatus([Project::STATUS_PO_PENDING, null, 0]);

        return $q->where(function ($q) {
            $q->amongStatus([Project::STATUS_BIDDING, Project::STATUS_PROSPECT, Project::STATUS_PENDING])
                // ->orWhereRaw("(status = ")
                ->orWhere(function ($q) {
                    $q->where('status', Project::STATUS_WON)
                        ->whereIn('po_status', [Project::STATUS_PO_PENDING, 0]);
                });
        })->with(['facility', 'estimators', 'salesPersons']);
    }
    
    public function scopeForSalesReport($q, $params = [])
    {
        // return $q->amongStatus([Project::STATUS_BIDDING, Project::STATUS_PROSPECT, Project::STATUS_PENDING, Project::STATUS_WON])
        //     ->amongPOStatus([Project::STATUS_PO_PENDING, null, 0]);

        return $q->where(function ($q) use($params) {

            if(@$params['date_from'] != ''){
                $q->whereDate('projects.created_at', '>=', DateTime::createFromFormat("m-d-Y", $params['date_from'])->format("Y-m-d")); 
            }
            if(@$params['date_to'] != ''){
                $q->whereDate('projects.created_at', '<=', DateTime::createFromFormat("m-d-Y", $params['date_to'])->format("Y-m-d")); 
            }


            $q->amongStatus([Project::STATUS_WON]);
        })->with(['facility', 'estimators', 'salesPersons']);
    }


    public function clients()
    {
        return $this->belongsToMany(Company::class, 'project_clients', 'project_id', 'client_id')
            ->using(ProjectCompanyPivot::class)->withPivot([
                'primary_contact_id',
                'primary_contacts'
            ]);
    }

    public function estimators()
    {
        return $this->belongsToMany(User::class, 'project_estimators', 'project_id', 'user_id')
            ->wherePivot('type', 'estimators');
    }

    public function salesPersons()
    {
        return $this->belongsToMany(User::class, 'project_estimators', 'project_id', 'user_id')
            ->wherePivot('type', 'sales');
    }

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }


    public function companies()
    {
        return $this->belongsToMany(Company::class, 'project_companies', 'project_id', 'company_id')
            ->using(ProjectCompanyPivot::class)->withPivot([
                'primary_contact_id',
                'primary_contacts'
            ])
            ->whereIn('companies.type', [\App\Models\Company::TYPE_SUBCONTRACTOR, \App\Models\Company::TYPE_SUPPLIER]);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'project_id');
    }

    public function calllogs()
    {
        return $this->hasMany(Calllog::class, 'project_id');
    }


    public function getAllContactIds()
    {
        return Contact::whereIn('company_id', $this->companies()->pluck('company_id')->toArray())->pluck('id')->toArray();
    }

    public function getEligibleContactsForMessage()
    {
        $contactId = [];
        $rows = DB::table('project_companies')->where('project_id', $this->id)->get();
        foreach ($rows as $row) {
            if ($row->primary_contacts) {
                $ar = collect(@json_decode($row->primary_contacts) ?? []);
                $contactId = array_merge($contactId, $ar->pluck('id')->toArray());
            }
        }
        // dd($contactId);

        return $contactId;
    }

    public function isWon()
    {
        return $this->status == self::STATUS_WON;
    }

    public function isLost()
    {
        return $this->status == self::STATUS_LOST;
    }

    public function isP0StatusAwarded()
    {
        return $this->po_status == self::STATUS_PO_AWARDED;
    }


    public function scopeForMe($q)
    {
        return $q;
        if (authUser()->isAdmin()) {
            return $q;
        }

        $ids = authUser()->assignedProjects()->pluck('project_id')->toArray();

        return $q->whereIn('id', $ids);
    }


    public function projectDate($date, $format = 'm-d-Y H:i')
    {
        return toTimezoneDate($date, $format, @$this->metadata['timezone']);
    }

    public function selfDestruct()
    {
        return $this->delete();
    }
}
