<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminSystem>
 */
class AdminSystemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => "Ba",
            'prenom' => "Adboul Magid",
            'email' => "bamagid60@gmail.com",
            'password' => Hash::make('password'),
            'telephone'=>775555555
        ];
    }
}
