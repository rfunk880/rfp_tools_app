<?php

namespace Support\Traits;


use Illuminate\Pipeline\Pipeline;

trait ProcessTasks
{
    public function run($payload)
    {
        return app(Pipeline::class)
            ->send($payload)
            ->through($this->tasks)
            ->thenReturn();
    }

    public function initialize($tasks = [])
    {
        $this->tasks = $tasks;
        return $this;
    }

    public function initializeIf($bool, $tasks = [])
    {
        if ($bool) {
            return $this->initialize($tasks);
        }

        return $this;
    }
}
