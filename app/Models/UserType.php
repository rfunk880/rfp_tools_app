<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'user_types';

    const SUPERADMIN = 20;
    const ADMIN = 10;
    const ESTIMATOR = 1;
    const SALESPERSON = 2;
    const VIEWER = 3;
    
    public static $typeLabel = [
        self::SUPERADMIN => '<span class="badge badge-pill bg-primary">Administrator</span>',
        self::ADMIN => '<span class="badge badge-pill bg-primary">Administrator</span>',
        self::ESTIMATOR => '<span class="badge badge-pill bg-dark">Estimator</span>',
        self::SALESPERSON => '<span class="badge badge-pill bg-info">Sales Person</span>',
        self::VIEWER => '<span class="badge badge-pill bg-danger">Viewer</span>',
    ];

    public function scopeExceptUser($q)
    {
        return $q->where('id', '!=', self::PARENT);
    }

    public static function PermitedGroupArray($exceptAdmin = false)
    {
        $groups = [];
        if(!$exceptAdmin){
            $groups[self::ADMIN] = 'Administrator';
        }
        $groups[self::ESTIMATOR] = 'Estimator';
        $groups[self::SALESPERSON] = 'Salesperson';
        $groups[self::VIEWER] = 'Viewer';
        return $groups;
    }
}
