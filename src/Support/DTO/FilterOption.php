<?php

namespace Support\DTO;

use Illuminate\Support\Str;
use Support\Filters\Constants;
use Support\Traits\ArrayToProps;

class FilterOption
{
    use ArrayToProps;
    public $label = null;
    public $component;
    public $id;
    public $multiple = false;
    public $name;
    public $value;
    public $options;
    public $placeholder = 'Search';
    public $conditions = [];
    public $dataKey;
    public $optionKey = 'id';
    public $optionLabel = 'name';
    public $type = Constants::TYPE_TEXT;
    public $validation = '';
    public $condition;
    public $meta = [];



    public function type($type)
    {
        $this->type = $type;
        return $this;
    }


    public function name($name)
    {
        $this->name = $name;
        $this->id = $name;
        if (is_null($this->label)) {
            $this->label = str_replace("_", " ", (Str::title($name)));
        }
        return $this;
    }


    public function id($id)
    {
        $this->id = $id;
        return $this;
    }


    public function label($label)
    {
        $this->label = $label;
        return $this;
    }


    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }


    public function options($options = [])
    {
        $this->options = $options;
        return $this;
    }


    public function component($component)
    {
        $this->component = $component;
        return $this;
    }


    public function condition($condition)
    {
        $this->condition = $condition;
        return $this;
    }


    public function value($value)
    {
        $this->value = $value;
        return $this;
    }


    public function validation($validation)
    {
        $this->validation = $validation;
        return $this;
    }


    public function meta($meta = [])
    {
        $this->meta = $meta;
        return $this;
    }

    public function multiple($bool){
        $this->multiple = (int) $bool;
        return $this;
    }

    public function build($ar = []){
        foreach($ar as $k => $v){
            $this->{$k} = $v;
        }
    }
}
