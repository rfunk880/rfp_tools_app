<?php
namespace Support\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait CreaterUpdaterTrait
{

    public static function bootCreaterUpdaterTrait()
    {
        /*if the table has user_id just set it to current id*/
        static::creating(function ($model) {
            /*$model->user_id = Auth::user();*/
            // echo ($model->created_by);
            if(!$model->created_by){
                $model->created_by = Auth::user() ? Auth::user()->id : 0;
            }
        });
        /*if the table has user_id just set it to current id*/
        static::updating(function ($model) {
            $model->updated_by = Auth::user() ? Auth::user()->id : 0;
            /*$model->user_id = Auth::user();*/
        });
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function scopeMine($query)
    {
        return $query->where('created_by', '=', Auth::user() ? Auth::user()->id : 0);
    }

    public function scopeOthers($query, $id)
    {
        return $query->where('created_by', '=', $id);
    }

    public function createdByMe()
    {
        return $this->created_by == (Auth::user() ? Auth::user()->id : 0);
    }

    public function updatedByMe()
    {
        return $this->updated_by == Auth::user() ? Auth::user()->id : 0;
    }

    public function createdByUser($id)
    {
        return $this->created_by == $id;
    }

    public function updatedByUser($id)
    {
        return $this->updated_by == $id;
    }


}
