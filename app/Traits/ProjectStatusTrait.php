<?php
namespace App\Traits;

use App\Models\Project;
use DateTime;
use App\Models\User;
use App\Models\UserType;

trait ProjectStatusTrait
{
    public function scopeAmongStatus($q, $status = [], $table = 'projects'){
        return $q->whereIn($table.'.status', $status);
    }


    public function scopeAmongPOStatus($q, $status = [], $table = 'projects'){
        return $q->whereIn($table.'.po_status', $status);
    }

   
}
