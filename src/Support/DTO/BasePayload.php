<?php

namespace Support\DTO;

use Illuminate\Validation\Factory;

class BasePayload
{
    public $rules = [];
    public $customAttributes = [];
    public $messages = [];
    
    public function validate()
    {
        return $this->getValidationFactory()->make(
            ((array) $this), $this->rules, $this->messages, $this->customAttributes
        )->validate();
    }

     /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    protected function getValidationFactory()
    {
        return app(Factory::class);
    }
}
