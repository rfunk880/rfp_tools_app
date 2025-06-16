<?php

namespace App\Traits;

use DateTime;
use App\Models\User;
use App\Models\UserType;

trait UserAuthTrait
{

    public function isSuperAdmin()
    {
        return $this->user_type_id == UserType::SUPERADMIN;
    }
    public function isAdmin()
    {
        return $this->user_type_id == UserType::ADMIN;
    }
    public function isEstimator()
    {
        return $this->user_type_id == UserType::ESTIMATOR;
    }
    public function isSalesPerson()
    {
        return $this->user_type_id == UserType::SALESPERSON;
    }
    public function isViewer()
    {
        return $this->user_type_id == UserType::VIEWER;
    }

    public function isUser()
    {
        return $this->user_type_id == UserType::VIEWER;
    }

    public function isAmongTypes($types)
    {
        return in_array($this->uesr_type_id, $types) || in_array((string) $this->user_type_id, $types);
    }

    public function changePassword($password)
    {
        $this->password = bcrypt($password);
        return $this->save();
    }

    public function lastLoginDateExceeded($days)
    {
        if (is_null($this->last_login_at)) {
            $this->updateLoginDate();
            return false;
        }
        $date1 = new DateTIme(date("Y-m-d"));
        $date2 = new DateTime($this->last_login_at);
        $diff = date_diff($date1, $date2, true);
        return $diff->d > $days;
    }

    public function updateLoginDate()
    {
        $this->last_login_at = date("Y-m-d H:i:s");
        $this->save();
    }

    public function isActive()
    {
        return $this->status == User::STATUS_ACTIVE;
    }

    public function scopeActive($q)
    {
        return $q->where('status', User::STATUS_ACTIVE);
    }


    public function canDo($permission, $guard = 'web')
    {
        if ($this->isAdmin()) {
            return true;
        }
        return $this->hasPermissionTo($permission, $guard);
        // return $this->roles->count() && $this->roles[0]->hasPermissionTo($permission);
    }


    public function saveSwitchableRoles($roles = [], $default = null)
    {
        $ar = [
            'roles' => array_unique($roles && count($roles) ? collect($roles)->merge($default)->filter(function ($item) {
                return $item;
            })->toArray() : []),
            'default' => $default
        ];

        $this->switchable_roles = $ar;
        return $this->save();
    }

    public function getSwitchableRoles()
    {
        return @$this->switchable_roles['roles'] ?? [];
    }
}
