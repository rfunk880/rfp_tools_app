<?php

namespace Support\Contracts;


interface ValidateRequest extends CanValidate
{

    /**
     * With
     *
     * @param array
     * @return self
     */
    public function with(array $input);

    
}
