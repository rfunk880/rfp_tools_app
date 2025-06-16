<?php

namespace Support\Filters;

use Illuminate\Support\Str;
use Support\DTO\FilterOption;
use Support\Contracts\Filters\Queryable;
use Illuminate\Database\Eloquent\Builder;
use Support\Contracts\Filters\BaseFilter;
use Support\Contracts\Filters\WithChildren;

class GeneralFilter extends BaseFilter implements Queryable, WithChildren
{

    public function query(Builder $builder, $value = null, $filters = [])
    {
        if (!$value) {
            return;
        }


        $q = $builder->where(function ($q) use ($value) {

            if (is_array($value)) {
                $this->multipleValues($q, $value);
            } else {
                $this->singleValue($q, $value);
            }
        });

        return $q;
    }

    public function children(): array
    {
        return [
            
        ];
    }

    public function singleValue($q, $value)
    {
        $condition = '=';
        switch ($this->option()->condition) {
            case Constants::CONDITION_IN:
                $condition = Constants::CONDITION_LIKE;
                break;
            case Constants::CONDITION_NOT_IN:
                $condition = Constants::CONDITION_NOT_LIKE;
                break;

            default:
                $condition = Constants::CONDITION_EQUALS_TO;
        }
        // pd($filter);
        $val = in_array($condition, [
            Constants::CONDITION_LIKE, Constants::CONDITION_NOT_LIKE
        ]) ? $value . '%' : $value;
        //ed($val);


        return $q->where($this->option()->name, $condition, $val);
    }

    public function multipleValues($q, $value)
    {
        if ($this->option()->condition == Constants::CONDITION_IN) {
            return $q->whereIn($this->option()->name, $value);
        } else {
            return $q->whereNotIn($this->option()->name, $value);
        }
    }
}
