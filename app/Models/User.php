<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\UserAuthTrait;
use App\Traits\UserHelperTrait;
use App\Traits\UserScopesTrait;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use InteractsWithMedia;
    use UserAuthTrait, UserScopesTrait, UserHelperTrait;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type_id',
        'status',
        'phone'
    ];

    public static $statusLabel = [
        self::STATUS_ACTIVE => '<span class="badge badge-pill bg-success">Active</span>',
        self::STATUS_INACTIVE => '<span class="badge badge-pill bg-danger">Inactive</span>',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'switchable_roles' => 'array'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl('/img/user_icon.png')
            ->useFallbackPath(public_path('/img/user_icon.png'));
    }

    public function switchableRoles()
    {
        return $this->belongsToMany(UserType::class, 'user_roles', 'user_id', 'role_id');
    }


    /**
     * User may have manciple social accounts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }


    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_estimators', 'user_id', 'project_id')->withPivot('type');
    }

    public function selfDestruct()
    {
        return $this->delete();
    }
}
