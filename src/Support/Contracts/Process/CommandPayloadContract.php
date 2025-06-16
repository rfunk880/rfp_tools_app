<?php

namespace Support\Contracts\Process;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface CommandPayloadContract
{

    public function model(): Model;

    public function request(): Request;
}
