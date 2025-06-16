<?php

namespace OptimaIt\Transcript\DTO;

use Illuminate\Support\Collection;
use Support\Traits\ArrayToProps;

class LanguagesCollection extends Collection
{
    public function __construct($items = []){
        $items = collect($items)->map(function($item){
            return new Language(@$item->languageCode, @$item->languageName->simpleText);
        })->toArray();
        $this->items = $this->getArrayableItems($items);
    }


    // public function all(){
    //     return parent::map(function($item){
    //         return new Language(@$item->languageCode, @$item->languageName);
    //     });
    // }
}
