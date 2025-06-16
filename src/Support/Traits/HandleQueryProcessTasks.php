<?php

namespace Support\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Support\Contracts\Process\QueryPayloadContract;

trait HandleQueryProcessTasks
{
    public function run(QueryPayloadContract $payload): mixed
    {
        return app(Pipeline::class)
            ->send($payload)
            ->through($this->tasks)
            ->thenReturn();
            // ->then(function ($payload) {
            //     dd(@$payload->course);
            //     return $payload;
            // });
            /* ->thenReturn() */;
    }
}
