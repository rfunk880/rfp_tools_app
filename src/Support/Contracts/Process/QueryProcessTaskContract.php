<?php

namespace Support\Contracts\Process;

use Closure;

interface  QueryProcessTaskContract
{
    public function __invoke(QueryPayloadContract $payload, Closure $next);
}
