<?php

namespace App\Models;

use App\Traits\LastUpdaterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    use HasFactory;
    use LastUpdaterTrait;

    protected $table = 'companies';
    protected $fillable = ['name', 'type', 'city', 'state', 'phone', 'notes'];
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;


    const TYPE_SUBCONTRACTOR = 'Subcontractor';
    const TYPE_SUPPLIER = 'Supplier';
    const TYPE_CLIENT = 'Client';

    public static $TYPES = [self::TYPE_SUBCONTRACTOR, self::TYPE_SUPPLIER, self::TYPE_CLIENT];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'company_id');
    }

    public function primaryContact()
    {
        return $this->hasOne(Contact::class, 'company_id')->primary();
    }

    public function getLocationAttribute()
    {
        return $this->city . ', ' . $this->state;
    }

    public function scopeFilter($q, $params = [])
    {
        return $q->where(function ($q) use ($params) {
            $tagIds = @$params['tags'] ?? [];

            $contactIds = [];
            if (count($tagIds) > 0) {

                $contactIds = DB::table('taggables')->whereIn('tag_id', $tagIds)
                    ->selectRaw("COUNT(tag_id) as totalTags, taggable_id")
                    ->where('taggable_type', '=', Contact::class)
                    ->havingRaw("totalTags = " . count($tagIds))
                    ->groupBy('taggable_id')
                    ->pluck('taggable_id')
                    ->toArray();

                    if(count($contactIds) == 0){
                        $contactIds = [0];
                    }
            }
            $keyword = urldecode(@$params['keyword']);
            if (@$keyword != '') {
                $regexKeyword = preg_replace("/[^A-Za-z0-9 ]/", "", $params['keyword']);
                // dd($tagIds);


                $q->where(function ($q) use ($keyword, $regexKeyword, $contactIds) {
                    $q
                        ->orWhere('companies.name', 'LIKE', '%' . $keyword . '%')
                        ->orWhereRaw("REGEXP_REPLACE(companies.name, '[^0-9a-zA-Z ]', '') LIKE  '%{$regexKeyword}%'")
                        ->orWhere('companies.city', 'LIKE', $keyword . '%')
                        ->orWhere('companies.state', $keyword)
                        ->orWhere('companies.phone', $keyword);
                });
            }

            if (@$params['company_type'] && is_array($params['company_type'])) {
                $q->whereIn('type', $params['company_type']);
            }

            if (count($contactIds)) {

                $q->whereHas('contacts', function ($q) use ($keyword, $contactIds) {



                    $q
                        ->whereIn('id', $contactIds);

                    if ($keyword != '') {
                        $q->orWhere(function ($q) use ($keyword) {

                            $q
                                ->where('contacts.name', 'LIKE', $keyword . '%')
                                ->orWhere('contacts.phone', 'LIKE', $keyword . '%')
                                ->orWhere('contacts.email', 'LIKE', '%' . $keyword . '%');
                        });
                    }

                    // if (count($tagIds)) {
                    //     $q->orWhereHas("tags", function ($q) use ($tagIds) {
                    //         return $q->whereIn('tag_id', $tagIds);
                    //     });
                    // }
                });
            }
        });
    }

    public function selfDestruct()
    {
        foreach($this->contacts as $contact){
            $contact->selfDestruct();
        }
        if($this->primaryContact){
            $this->primaryContact->selfDestruct();
        }
        return $this->delete();
    }
}
