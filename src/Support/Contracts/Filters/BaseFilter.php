<?php

namespace Support\Contracts\Filters;

use Support\DTO\FilterOption;
use Illuminate\Database\Eloquent\Builder;
use Support\Collections\FilterCollection;
use Support\DTO\FilterInput;

abstract class BaseFilter
{
    protected FilterOption $option;

    public FilterCollection $children;

    public function __construct(FilterOption $option)
    {
        $this->option = $option;
    }

    /* default buildOption empty method */
    public function buildOptions(FilterOption $option): void
    {
    }

    public function option(): FilterOption
    {
        return $this->option;
    }

    abstract public function query(Builder $builder, $value = null, $filter = []);

    /* default render */
    public function render()
    {
        return (new FilterInput($this->option()))->toArray();
    }

    /* with children default implementation */
    public function setChildren(FilterCollection $filterCollection){
        $this->children = $filterCollection;
    }


    public function authorize()
    {
        return true;
    }

    // /**
    //  * Determine if the filter passes the authorization check.
    //  *
    //  * @return bool
    //  *
    //  */
    // protected function passesAuthorization()
    // {
    //     if (method_exists($this, 'authorize')) {
    //         return $this->authorize();
    //     }

    //     return true;
    // }


    public function initialize($ar){
        if (is_array($ar) && count($ar) > 0) {
            foreach ($ar as $k => $v) {
                if (!is_numeric($k)) {
                    $this->option()->{$k} = $v;
                }
            }
            if (isset($ar['multiple'])) {
                $this->option()->value = [];
            }
            $this->generateID();
        }
    }

    public function generateID(){
        $this->option()->id('filter-'.md5($this->option()->name.'-'.get_class($this)));
    }
}
