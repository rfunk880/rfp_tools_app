<?php

namespace Support\Traits;


trait FlushModelEvents
{
    public static function flushEventListenersProvided($events = [])
    {
        if (!isset(static::$dispatcher)) {
            return;
        }
        $instance = new static;
        foreach ($instance->getObservableEvents() as $event) {
            if (count($events) == 0 || in_array($event, $events)) {
                static::$dispatcher->forget("eloquent.{$event}: " . get_called_class());
            }
        }
    }
}
