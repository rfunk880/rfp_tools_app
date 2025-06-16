<?php

namespace Support\Traits;

use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

trait ArrayToProps
{
    public static function FromArray($propsArray = [])
    {
        $reflector = new ReflectionClass(static::class);
        return $reflector->newInstanceArgs($propsArray);
    }

    public static function LazyFromArray($propsArray = [])
    {
        $obj = new static();

        $publicProperties = (new ReflectionObject($obj))->getProperties(ReflectionProperty::IS_PUBLIC);
        // dd(get_object_vars($obj) );
        foreach ($publicProperties as $property) {
            $var = $property->name;

            if (isset($propsArray[$var])) {
                $obj->{$var} = $propsArray[$var];
            }
        }

        return $obj;
    }



    public function clean()
    {
        return collect((array) $this)->filter(function ($item) {
            return !is_null($item);
        })->toArray();
    }

    public function toArray($clean = false)
    {
        if (!$clean) {
            return (array) $this;
        }
        return $this->clean();
    }
}
