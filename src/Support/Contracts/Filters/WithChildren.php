<?php

namespace Support\Contracts\Filters;

use Illuminate\Database\Eloquent\Builder;
use Support\Collections\FilterCollection;

interface WithChildren
{

    public function setChildren(FilterCollection $filterCollection): void;
    
    public function children(): array;
}
