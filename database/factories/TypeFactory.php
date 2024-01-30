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
            'nom' =>  $this->faker->randomElement(['urbain', 'banlieu', 'diamniadio']),
            'description' => 'Ces lignes ne concernent que la  zone a la quelle elles sont assignées',
            'reseau_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
