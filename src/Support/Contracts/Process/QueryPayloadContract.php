<?php

namespace Support\Contracts\Process;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

interface QueryPayloadContract
{

    public function queryBuilder(): Builder;

    public function request(): Request;
}
