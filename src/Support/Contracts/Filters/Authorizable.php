<?php

namespace Support\Contracts\Filters;

interface Authorizable
{

    public function authorize(): bool;
}
