<?php

namespace Support\Collections;


use Illuminate\Support\Collection;
use Support\Contracts\Filters\Renderable;
use Support\Contracts\Filters\Authorizable;
use Support\Contracts\Filters\WithChildren;

class FilterCollection extends Collection
{

    public function update($keyValuePair = [], $property = 'options')
    {
        $this->map(function ($item) use ($keyValuePair, $property) {
            if (isset($keyValuePair[$item->key])) {
                $item->{$property} = $keyValuePair[$item->key];
            }
        });

        return $this;
    }

    public function toSchema()
    {
        $items = [];
        foreach ($this->items as $filter) {
            if ($filter instanceof Authorizable && !$filter->authorize()) {
                continue;
            }
            if ($filter instanceof Renderable) {
                $filter->buildOptions($filter->option());
                $item = $filter->render();
                if ($filter instanceof WithChildren && count($filter->children()) > 0) {
                    $item['children'] = $filter->children->toSchema();
                }

                $items[] = $item;
            }
        }

        return $items;
    }
}
