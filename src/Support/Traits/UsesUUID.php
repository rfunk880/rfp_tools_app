<?php
namespace Support\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait UsesUUID
{
    /**
     * Generates a UUID during model creation.
     */
    public static function bootUsesUuid(): void
    {
        static::creating(
            static function (Model $model) {
                $col = $model->getUUIDColumn();
                if (! isset($model->{$col})) {
                    $model->{$col} = Str::uuid()->toString();
                }
            }
        );
    }

    public function scopeWhereUUID($q, $uuid){
        return $q->where($this->getUUIDColumn(), $uuid);
    }


    public static function findByUUID($uuid){
        return self::whereUUID($uuid)->first();
    }

    public static function findOrFailByUUID($uuid){
        $model = self::findByUUID($uuid);
        if(!$model){
            throw new ModelNotFoundException("Not Found");
        }

        return $model;
    }

    public function getUUIDColumn(){
        return 'uuid';
    }
}
