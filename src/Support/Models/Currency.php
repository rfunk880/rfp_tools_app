<?php

namespace Support\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    const NPR = 1;
    const USD = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name'
    ];

    /**
     * Set currency code in capital
     *
     * @param  string  $value
     * @return void
     */
    public function setCodeAttribute($code)
    {
        $this->attributes['code'] = strtoupper($code);
    }

    /**
     * Get the currency_exchange associated with the currency.
     */
    public function CurrencyExchangeRate()
    {
        return $this->hasOne(CurrencyExchangeRate::class, 'target_currency');
    }

}