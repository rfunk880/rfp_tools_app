<?php

namespace Support\Contracts\Filters;

use Illuminate\Database\Eloquent\Builder;

interface Queryable
{
    public function query(Builder $builder, $value = null, $filters = []);
}
