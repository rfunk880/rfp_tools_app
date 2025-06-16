<?php
/**
 * Created by PhpStorm.
 * User: baghraja
 * Date: 9/18/19
 * Time: 8:43 PM
 */

namespace Support\Models;


use Illuminate\Database\Eloquent\Model;
use Themightysapien\Organization\Traits\BelongsToOrganizationTrait;

class UserSetting extends Model
{
    use BelongsToOrganizationTrait;
    protected $table = 'user_settings';
    protected $fillable = ['key', 'options'];

    protected $casts = [
        'options' => 'array'
    ];
}