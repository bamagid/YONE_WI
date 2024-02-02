<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Abonnement>
 */
class AbonnementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Mensuel', 'Trimestriel', 'Annuel'];
        $durees = ['1 mois', '3 mois', '12 mois'];
        $descriptions = [
            'Abonnement standard avec accès illimité.',
            'Abonnement premium avec avantages exclusifs.',
            'Abonnement économique pour un accès de base.'
        ];

        return [
            'prix' => fake()->numberBetween(5000, 50000),
            'type' => fake()->randomElement($types),
            'duree' => fake()->randomElement($durees),
            'description' => fake()->randomElement($descriptions),
            'reseau_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
