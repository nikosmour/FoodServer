<?php

namespace App\Enum;

use App\Traits\EnumToArrayTrait;

enum UserStatusEnum: string
{
    use EnumToArrayTrait;

    case UNDERGRADUATE = 'undergraduate';
    case POSTGRADUATE = 'postgraduate';
    case PHD = 'phd';
    case ERASMUS = 'erasmus';
    case RESEARCHER = 'researcher';
    case STAFF_COUPON = 'staff coupon';
    case STAFF_CARD = 'staff card application';
    case STAFF_ENTRY = 'staff entry';

    /**
     * determine if the user has the specific $role
     * @param UserRoleEnum $role
     * @return bool
     */
    public function hasRole(UserRoleEnum $role): bool
    {
        return $this->hasAnyRole([$role]);
    }

    /**
     * determine if the user has any of the specific $roles
     * @param UserRoleEnum[] $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role(), $roles);
    }

    /**
     * @return UserRoleEnum
     */
    private function role(): UserRoleEnum
    {
        return match ($this) {
            UserStatusEnum::STAFF_CARD => UserRoleEnum::STAFF_CARD,
            UserStatusEnum::STAFF_COUPON => UserRoleEnum::STAFF_COUPON,
            UserStatusEnum::STAFF_ENTRY => UserRoleEnum::STAFF_ENTRY,
            UserStatusEnum::UNDERGRADUATE,
            UserStatusEnum::POSTGRADUATE,
            UserStatusEnum::PHD,
            UserStatusEnum::ERASMUS => UserRoleEnum::STUDENT,
            UserStatusEnum::RESEARCHER => UserRoleEnum::RESEARCHER,
        };
    }

    /**
     * determine if the user status has the specific $ability
     * @param UserAbilityEnum $ability
     * @return bool
     */
    public function hasAbility(UserAbilityEnum $ability): bool
    {
        return $this->hasAnyRole($ability->whoHas());
    }
    /**
     * determine if the user status has any of the specifics $abilities
     * @param UserAbilityEnum[] $abilities
     * @return bool
     */
    public function hasAnyAbility(array $abilities): bool
    {
        foreach ($abilities as $ability)
            if($this->hasAbility($ability))
                return true;
        return false;
    }


}
