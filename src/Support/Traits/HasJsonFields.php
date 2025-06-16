<?php

namespace Support\Traits;


trait HasJsonFields
{
    public function updateJsonField($array, $column = 'data')
    {
        $this->mergeJsonField($array, $column);
        // dd($this);
        return $this->save();
    }

    public function mergeJsonField($array, $column = 'data')
    {
        // dd($column);
        $data = $this->{$column};
        if (!$data) {
            $data = [];
        }

        $this->{$column} = array_merge($data, $array);
        // dd($this->{$column});
    }
}
