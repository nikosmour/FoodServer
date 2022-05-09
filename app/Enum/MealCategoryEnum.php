<?php

namespace App\Enum;

use App\Traits\EnumToArrayTrait;

enum MealCategoryEnum: string
{
    use EnumToArrayTrait;

    case BREAKFAST = 'breakfast';
    case MEAL = 'meal';
    case SALAD = 'salad';
    case EXTRA = 'extra';
}
