<?php

namespace Database\Factories;

use App\Enum\MealPlanPeriodEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class UsageCouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'period' => MealPlanPeriodEnum::random(),
        ];
    }
}
