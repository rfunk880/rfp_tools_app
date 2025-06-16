<?php
namespace Support\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

trait DefaultOrderBy
{

    protected static function boot()
    {
        parent::boot();
   
        // Default order by column
        $column = self::$orderByColumn;
   
        // Use the default order by on the config if its not set
        $direction = isset(self::$orderByColumnDirection)
               ? self::$orderByColumnDirection
               : config('default-model-sorting.order_by');
   
        // Add default order by column to any Eloquent model that uses this trait
        static::addGlobalScope('default_order_by', function (Builder $builder) use ($column, $direction) {
               $builder->orderBy($column, $direction);
           });
   } 


}
