<?php

namespace Support\Factories;


use ReflectionClass;
use Support\Filters\GeneralFilter;
use Support\Collections\FilterCollection;
use Support\Contracts\Filters\WithChildren;
use Support\DTO\FilterOption;

class FilterFactory
{
    public static function FromArray($filterAr)
    {
        $filterCollection = new FilterCollection();
        foreach ($filterAr as $ar) {
            $className = GeneralFilter::class;
            $key = @array_values($ar)[0];
            if (class_exists($key)) {
                $className = $key;
            }
            $reflect = new ReflectionClass($className);

            $filter = $reflect->newInstanceArgs([new FilterOption()]);

            $filter->initialize();
            // if (is_array($ar) && count($ar) > 0) {
            //     foreach ($ar as $k => $v) {
            //         if (!is_numeric($k)) {
            //             $filter->option()->{$k} = $v;
            //         }
            //     }
            //     if (isset($ar['multiple'])) {
            //         $filter->option()->value = [];
            //     }
            //     $filter->generateID();
            // }

            if($filter instanceof WithChildren && count($filter->children()) > 0){
                $filter->setChildren(self::FromArray($filter->children()));
            }

           
            $filterCollection->push($filter);
        }

        return $filterCollection;
    }


    public static function Create($user, $key)
    {
        return $user->settings()->filter()->firstOrNew([
            'key' => $key,
            'context' => 'filter'
        ]);

    }


    public static function NewFilter($user, $key, $value = [])
    {
        $filter = self::Create($user, $key);

        $data = $filter->data;

        if (!$data) {
            $data = [];
        }

        $data[] = $value;

        $filter->data = $data;
        $filter->save();

        return $filter;
    }
}
