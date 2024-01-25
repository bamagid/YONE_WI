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
            'etat' => $this->faker->randomElement(['actif', 'corbeille', 'supprimé']),
            'reseau_id' => $this->faker->randomElement([1, 2]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
