<?php

namespace Support\Traits;


use Illuminate\Pipeline\Pipeline;
use Illuminate\Database\Eloquent\Model;
use Support\Contracts\Process\QueryPayloadContract;
use Support\Contracts\Process\CommandPayloadContract;

trait HandleCommandProcessTasks
{
    public function run(CommandPayloadContract $payload)
    {
        return app(Pipeline::class)
            ->send($payload)
            ->through($this->tasks)
            ->thenReturn();
            
    }

    public function initialize($tasks = []){
        $this->tasks = $tasks;
        return $this;
    }

    public function initializeIf($bool, $tasks = []){
        if($bool){
            return $this->initialize($tasks);
        }

        return $this;
    }
}
