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
            'Depart' => fake()->randomElement(["Keur Massar,Mbao,Ouakam,Median"]),
            'Arrivee' => fake()->randomElement(["mariste", "petersen", "pikine", "guediawaye"]),
            'ligne_id' => fake()->randomElement([1, 2]),
            'tarif_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
