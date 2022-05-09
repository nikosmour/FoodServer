<?php

namespace App\Enum;

use App\Traits\EnumToArrayTrait;

enum MealPlanPeriodEnum: string
{
    use EnumToArrayTrait;

    case BREAKFAST = 'breakfast';
    case LUNCH = 'lunch';
    case DINNER = 'dinner';

    /**
     * Find the current meal period
     * @return MealPlanPeriodEnum
     */
    public static function getCurrentMealPeriod(): MealPlanPeriodEnum
    {
        $hours = (int)date('H');
        if ($hours < 12)
            return MealPlanPeriodEnum::BREAKFAST;
        elseif ($hours < 18)
            return MealPlanPeriodEnum::LUNCH;
        else
            return MealPlanPeriodEnum::DINNER;
    }
}
