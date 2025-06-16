<?php

namespace Support\Traits;

use Support\Factories\FilterFactory;
use Support\Contracts\Filters\WithChildren;

trait Filterable
{

    public function scopeFilter($q, $params = [])
    {
        if (!isset(self::$FILTERS)) {
            return $q;
        }
        $this->executeFilter(FilterFactory::FromArray(self::$FILTERS), $params);
    }

    public function executeFilter($filters, $params)
    {
        $filters->each(function ($filter) use ($q, $params) {
            if (@$params[$filter->option()->name] != '') {
                $filter->query($q, $params[$filter->option()->name], $params);
            }

            if ($filter instanceof WithChildren && count($filter->children()) > 0) {
                $this->executeFilter($filter->children, $params);
            }
        });
    }
}
