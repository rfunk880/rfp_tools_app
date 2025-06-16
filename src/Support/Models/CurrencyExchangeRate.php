<?php

namespace Support\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyExchangeRate extends Model
{
    protected $fillable = [
        'target_currency', 'rate'
    ];

}