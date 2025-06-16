<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    protected $fillable = ['name', 'label', 'module'];
    protected $guard_name = 'web';
}
