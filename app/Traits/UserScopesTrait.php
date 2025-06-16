<?php

namespace App\Traits;

use DateTime;
use App\Models\User;
use App\Models\UserType;

trait UserScopesTrait
{
    public function scopeExceptMe($q)
    {
        return $q->where('id', '!=', auth()->user()->id);
    }

    public function scopeExceptAdmin($q)
    {
        return $q->where('user_type_id', '!=', UserType::ADMIN);
    }

    public function scopeOnlyAdmin($q)
    {
        return $q->where('user_type_id', UserType::ADMIN);
    }

    public function scopeSalesPerson($q)
    {
        return $q->where('user_type_id', UserType::SALESPERSON);
    }

    public function scopeEstimator($q)
    {
        return $q->where('user_type_id', UserType::ESTIMATOR);
    }

    public function scopeOnlyTypes($q, $types = [])
    {
        return $q->whereIn('user_type_id', $types);
    }

    public function scopeAmongTypes($q, $types = [])
    {
        return $q->whereIn('user_type_id', $types);
    }

    public function scopeExceptTypes($q, $types = [])
    {
        return $q->whereNotIn('user_type_id', $types);
    }
}
