<?php

namespace Support\Filters;

use Support\Contracts\Filters\Queryable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Renderable;
use Support\Contracts\Filters\BaseFilter;
use Support\DTO\FilterOption;

class SortFilter extends BaseFilter implements Queryable, Renderable
{

    public function buildOptions(FilterOption $option): void
    {
        $option
            ->options([['value' => 'created_at', 'label' => 'Created Date']])
            ->value('created_at')
            ->label("Sort By");
    }


    public function query(Builder $builder, $value = null, $filters = [])
    {

        return $builder->orderBy($value, @$filters[$this->sortTypeName]);
    }
}
