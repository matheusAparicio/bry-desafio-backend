<?php

namespace Database\Factories;

use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'login' => fake()->unique()->userName(),
            'name' => fake()->name(),
            'cpf' => fake()->cpf(),
            'email' => fake()->unique()->safeEmail(),
            'type' => fake()->randomElement(['employee', 'customer']),
            'address' => fake()->address(),
            'file_id' => null,
            'password' => Hash::make('password'),
        ];
    }
}
