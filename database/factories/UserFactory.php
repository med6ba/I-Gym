<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'member',
            'status' => 'active',
            'language' => 'en',
            'theme' => 'light',
            'currency' => 'MAD',
            'age' => fake()->numberBetween(18, 55),
            'height_cm' => fake()->numberBetween(155, 195),
            'weight_kg' => fake()->numberBetween(55, 105),
            'gender' => fake()->randomElement(['female', 'male', 'other', 'prefer_not_to_say']),
            'fitness_goal' => fake()->randomElement(['weight_loss', 'muscle_gain', 'fitness', 'endurance']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
