<?php

namespace App\Models;

use App\Enum\UserAbilityEnum;
use App\Enum\UserStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * @var string[]
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => UserStatusEnum::class,
    ];

    /**
     * @param UserAbilityEnum $ability
     * @return bool
     */
    public function hasAbility(UserAbilityEnum $ability): bool
    {
        return $this->status->can($ability);
    }

    /**
     * check if the instance has an ability
     * @param UserAbilityEnum[] $abilities
     * @return bool
     */
    public function hasAnyAbility(array $abilities): bool
    {
        return $this->status->canAny($abilities);
    }
}
