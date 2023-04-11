<?php

namespace Database\Factories;

use App\Enum\MealCategoryEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category' => MealCategoryEnum::random(),
            'description' => 'fake' . now()->toDateTimeString(),
        ];
    }
}
