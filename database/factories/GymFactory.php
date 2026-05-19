<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GymFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->company().' Fitness';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(100, 999),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'status' => $this->faker->randomElement(['active', 'trial', 'expired', 'suspended']),
            'subscription_plan' => $this->faker->randomElement(['basic', 'pro', 'business']),
            'subscription_started_at' => now()->subMonths(2),
            'subscription_ends_at' => now()->addMonths(2),
        ];
    }
}
