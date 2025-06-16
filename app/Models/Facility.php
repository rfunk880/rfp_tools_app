<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'facilities';
    protected $fillable = ['name', 'owner', 'location', 'is_key_account'];

    public function scopeFilter($q, $params = [])
    {
        return $q->where(function ($q) use ($params) {
            if (@$params['keyword'] != '') {
                $keyword = urldecode($params['keyword']);
                $q->where(function ($q) use ($keyword) {
                    $q
                        ->orWhere('name', 'LIKE', '%' . $keyword . '%')

                        // // ->orWhere('city', 'LIKE', '%' . $keyword . '%')
                        // ->orWhere('state', $keyword)
                        // ->orWhere('phone', $keyword)
                    ;
                });
            }
        });
    }

    public function projects(){
        return $this->hasMany(Project::class, 'facility_id');
    }


    public function selfDestruct()
    {
        return $this->delete();
    }
}
