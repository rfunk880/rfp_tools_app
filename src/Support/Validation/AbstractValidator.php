<?php

namespace Support\Validation;


abstract class AbstractValidator
{
    /**
     * Validator
     *
     * @var object
     */
    protected $validator;

    /**
     * Data to be validated
     *
     * @var array
     */
    protected $data = [];

    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [];

    protected $messages = [];

    protected $activeRule = 'default';


    /**
     * Set data to validate
     *
     * @param array $data
     * @return self
     */
    public function with(array $data, $message = [])
    {
        $this->data = $data;
        $this->setMessages($message);

        return $this;
    }


    /**
     * Pass the data and the rules to the validator
     *
     * @return array
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    abstract function validate();

    /**
     * @param array $newRules set of new validation rules for the current model
     */
    public function setActive($newActive)
    {
        $this->activeRule = $newActive;
        return $this;
    }
    public function setDefault($newActive)
    {
        $this->activeRule = $newActive;
        return $this;
    }

    public function forRequest($rule)
    {
        return $this->setActive($rule);
    }

    public function forContext($newDetault)
    {
        return $this->setActive($newDetault);
    }

    public function when($newActive)
    {
        return $this->setActive($newActive);
    }

    public function setMessages($ar)
    {
        $this->messages = $ar;
        return $this;
    }
}
