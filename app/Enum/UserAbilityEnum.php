<?php

namespace App\Enum;

use App\Interfaces\Ability;
use App\Interfaces\Enum;
use App\Traits\Enums\EnumTrait;

enum UserAbilityEnum: string implements Enum, Ability
{
    use EnumTrait;

    case COUPON_OWNERSHIP = 'coupon ownership';
    case COUPON_SELL = 'coupon sell';
    case CARD_APPLICATION_CHECK = 'card application check';
    case CARD_OWNERSHIP = 'card ownership';
    case DAILY_MEAL_PLAN_CREATE = 'daily meal plan create';
    case ENTRY_CHECK = 'entry check';
}
