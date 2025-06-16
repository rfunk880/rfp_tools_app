<?php

namespace Support\Contracts\Process;


interface  CommandProcessContract
{
    public function run(CommandPayloadContract $payload);
}
