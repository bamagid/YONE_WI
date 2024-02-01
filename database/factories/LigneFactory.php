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
            'nom' => $this->faker->numberBetween(1, 100),
            'type_id' => $this->faker->randomElement([1, 2]),
            'reseau_id' => 1,
            'lieuDepart' => $this->faker->randomElement(['Rufisque', 'Malika', "Diamaguene"]),
            'lieuArrivee' => $this->faker->randomElement(['Sandaga', 'Sahm', "Palais2"]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
