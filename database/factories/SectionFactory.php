<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Depart' => $this->faker->randomElement(["Keur Massar,Mbao,Ouakam,Median"]),
            'Arrivee' => $this->faker->randomElement(["mariste", "petersen", "pikine", "guediawaye"]),
            'etat' => $this->faker->randomElement(['actif', 'corbeille', 'supprimé']),
            'ligne_id' => $this->faker->randomElement([1, 2]),
            'tarif_id' => $this->faker->randomElement([1, 2]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
