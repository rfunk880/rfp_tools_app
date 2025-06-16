<?php

namespace Support\Traits;




trait CanBroadcastToOthers
{

    public static function bootCanBroadcastToOthers()
    {

        static::created(function ($model) {
            switch (get_class($model)) {
                case  Message::class:

                    break;
            }
        });
    }


    public function broadcastToOthers()
    {
        switch (get_class($this)) {
            case  Message::class:
                broadcast(new MessageSent($this))->toOthers();
                break;

            case  Notification::class:
                broadcast(new Notify($this))->toOthers();
                break;
        }
    }
}
