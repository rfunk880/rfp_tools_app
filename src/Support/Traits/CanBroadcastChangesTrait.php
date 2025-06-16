<?php

namespace Support\Traits;

use App\Events\RealTimeModelChanges;

trait CanBroadcastChangesTrait
{

    public function broadcastChanges($changes = [], $action = null)
    {
        broadcast(new RealTimeModelChanges($this, $changes, $action))->toOthers();
    }

    // public function broadcastToAll($changes = [], $action = null)
    // {
    //     broadcast(new RealTimeModelChanges($this, $changes, $action));
    // }
}
