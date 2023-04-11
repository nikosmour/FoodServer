<?php

namespace Database\Factories;

use App\Enum\CardStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class CardApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['expiration_date' => "\DateTime", 'status' => "mixed"])]
    public function definition(): array
    {
        return [
            'expiration_date' => $this->faker->dateTimeBetween('-1 years', '+1 years'),
            'status' => CardStatusEnum::random(),
        ];
    }
}
