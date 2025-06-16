<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use HasFactory;

    const ADMIN = 10;
    const CLIENT = 1;

    CONST STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const ACCESS_TYPE_WEB = 1;
    const ACCESS_TYPE_MOBILE = 2;

    protected $guard_name = 'web';


    protected $fillable = ['name', 'color', 'status', 'access_type', 'guard_name'];

    public function scopeFilter($q, $params = [], $table = 'users.')
    {
        return $q->where(function ($q) use ($params, $table) {
            if (@$params['keyword'] != '') {
                $q->where('name', 'LIKE', "%{$params['keyword']}%")
                    ;
            }
        });
    }
}
