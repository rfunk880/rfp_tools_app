<?php

namespace Support\Models;

use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
    protected $fillable = [
        'code', 'name'
    ];

}