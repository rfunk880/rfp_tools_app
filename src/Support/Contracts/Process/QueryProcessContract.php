<?php

namespace Support\Contracts\Process;


interface  QueryProcessContract
{
    public function run(QueryPayloadContract $payload);
}
