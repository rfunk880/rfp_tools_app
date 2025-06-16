<?php

namespace Support\Contracts;


interface CanValidate
{
   /**
     * Passes
     *
     * @return array
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate();
}
