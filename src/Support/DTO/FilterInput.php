<?php

namespace Support\DTO;

class FilterInput
{
    private $option;

    public function __construct(FilterOption $option)
    {
        $this->option = $option;
    }

    public function toArray()
    {
        return [
            'name' => $this->option->name,
            'type' => $this->option->type,
            'placeholder' => $this->option->placeholder,
            'options' => $this->option->options,
            'label' => $this->option->label,
            'id' => $this->option->id,
            'uuid' => $this->option->uuid,
            'optionKey' => $this->option->optionKey,
            'optionLabel' => $this->option->optionLabel,
            'meta' => $this->option->meta
        ];
    }
}
