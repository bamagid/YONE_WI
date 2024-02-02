<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ligne>
 */
class LigneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->numberBetween(1, 100),
            'type_id' => fake()->randomElement([1, 2]),
            'reseau_id' => 1,
            'lieuDepart' => fake()->randomElement(['Rufisque', 'Malika', "Diamaguene"]),
            'lieuArrivee' => fake()->randomElement(['Sandaga', 'Sahm', "Palais2"]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
