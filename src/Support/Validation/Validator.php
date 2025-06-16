<?php

namespace Support\Validation;


use Support\Contracts\ValidateRequest;
use Illuminate\Validation\Factory;
use Support\Validation\AbstractValidator;
use Illuminate\Validation\ValidationException;

class Validator extends AbstractValidator implements ValidateRequest
{

    /**
     * Construct
     *
     * @param Illuminate\Validation\Factory;
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }


    /**
     * Pass the data and the rules to the validator
     *
     * @return array
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    function validate()
    {
        return $this
            ->validator
            ->make($this->data, $this->rules[$this->activeRule], $this->messages)
            ->validate();
    }
}
