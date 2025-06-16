<?php

namespace Support\Filters;

use App\User;
use Support\Contracts\Filters\Queryable;
use Illuminate\Database\Eloquent\Builder;
use Support\Contracts\Filters\BaseFilter;
use Support\Contracts\Filters\Searchable;

class AsyncUserFilter extends BaseFilter implements Queryable, Searchable
{

    /* Filter Query */
    public function query(Builder $builder, $value = null, $filters = [])
    {
        $user = User::findByUUID(@$value['uuid']);

        return $builder->where('owner_id', $user->id);
    }


    /* Search Results for autocomplete on clientside */
    public function search($value)
    {
        return User::quickSearch($value)->limit(10)->get();
    }
}
