<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransferCoupon>
 */
class TransferCouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'breakfast'=>$this->faker->numberBetween(0,255),
            'lunch'=>$this->faker->numberBetween(0,255),
            'dinner'=>$this->faker->numberBetween(0,255),
        ];
    }
}
