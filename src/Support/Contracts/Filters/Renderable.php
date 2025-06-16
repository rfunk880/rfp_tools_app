<?php

namespace Support\Contracts\Filters;

use Support\DTO\FilterOption;

interface Renderable
{
    /**
    * Additional options required for the ui
    *
    * @return array 
    */
    public function buildOptions(FilterOption $option): void;

    public function option(): FilterOption;


    public function render(): array;
}
