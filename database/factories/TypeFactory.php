<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Type>
 */
class TypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->randomElement(['urbain', 'banlieu', 'diamniadio']),
            'reseau_id' => 1,
            'description' => 'Ces lignes ne concernent que la  zone a la quelle elles sont assignées',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
