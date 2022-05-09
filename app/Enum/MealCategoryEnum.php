<?php

namespace App\Enum;

use App\Interfaces\Enum;
use App\Traits\Enums\EnumTrait;


enum MealCategoryEnum: string implements Enum
{
    use EnumTrait;

    case BREAKFAST = 'breakfast';
    case MEAL = 'meal';
    case SALAD = 'salad';
    case EXTRA = 'extra';
}
