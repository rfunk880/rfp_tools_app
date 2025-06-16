<?php

namespace Support\Contracts\Process;

use Closure;

interface  CommandProcessTaskContract
{
    public function __invoke(CommandPayloadContract $payload, Closure $next);
}
