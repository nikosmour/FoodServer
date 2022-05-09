<?php

namespace App\Enum;

use App\Traits\EnumToArrayTrait;

enum UserAbilityEnum: string
{
    use EnumToArrayTrait;

    case COUPON_OWNERSHIP = 'coupon ownership';
    case COUPON_SELL = 'coupon sell';
    case CARD_APPLICATION_CHECK = 'card application check';
    case CARD_OWNERSHIP = 'card ownership';
    case DAILY_MEAL_PLAN_CREATE = 'daily meal plan create';
    case ENTRY_CHECK = 'entry check';

    /**
     * @return array of UserRoleEnum
     * return an array of UserRoleEnum that have this ability
     */
    public function whoHas(): array
    {
        return match ($this) {
            UserAbilityEnum::COUPON_OWNERSHIP => [UserRoleEnum::RESEARCHER,UserRoleEnum::STUDENT],
            UserAbilityEnum::COUPON_SELL => [UserRoleEnum::STAFF_COUPON],
            UserAbilityEnum::CARD_APPLICATION_CHECK => [UserRoleEnum::STAFF_CARD],
            UserAbilityEnum::CARD_OWNERSHIP => [UserRoleEnum::STUDENT],
            UserAbilityEnum::DAILY_MEAL_PLAN_CREATE => [UserRoleEnum::STAFF_COUPON,UserRoleEnum::STAFF_ENTRY],
            UserAbilityEnum::ENTRY_CHECK => [UserRoleEnum::STAFF_ENTRY],

        };
    }

}
