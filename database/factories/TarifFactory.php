<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarif>
 */
class TarifFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prix' => $this->faker->numberBetween(150, 50),
            'type' =>  $this->faker->randomElement(['normal', 'entre section',]),
            'reseau_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
