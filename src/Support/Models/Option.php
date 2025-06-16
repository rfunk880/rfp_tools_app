<?php

namespace Support\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['code', 'value'];

    protected $casts = [
        'value' => 'array'
    ];
}