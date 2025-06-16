<?php

namespace Support\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait UsesToggleTrait
{
    public function scopeActive($q)
    {
        return $q->where($this->getToggleableColumn(), $this->getActiveValue());
    }

    public function scopeDisabled($q)
    {
        return $q->where($this->getToggleableColumn(), $this->getDisabledValue());
    }

    public function toggle()
    {
        $val = $this->{$this->getToggleableColumn()};

        $this->{$this->getToggleableColumn()} = $val == $this->getActiveValue() ? $this->getDisabledValue() : $this->getActiveValue();
        return $this->save();
    }


    public static function findByUUID($uuid)
    {
        return self::whereUUID($uuid)->first();
    }

    public static function findOrFailByUUID($uuid)
    {
        $model = self::findByUUID($uuid);
        if (!$model) {
            throw new ModelNotFoundException("Not Found");
        }

        return $model;
    }

    /* override this in models for custom column */
    public function getToggleableColumn()
    {
        return 'status';
    }
    /* override this in models for custom active value */
    public function getActiveValue()
    {
        return 1;
    }
    /* override this in models for custom disabled value */
    public function getDisabledValue()
    {
        return 0;
    }
}
