<?php

namespace Database\Factories;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gym_id' => Gym::factory(),
            'user_id' => User::factory()->state(['role' => 'member']),
            'plan_name' => $this->faker->randomElement(['Monthly Access', 'Premium Coaching', 'Student Plan']),
            'price' => $this->faker->randomElement([199, 299, 599]),
            'starts_at' => now()->subMonth()->toDateString(),
            'ends_at' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'payment_status' => 'paid',
        ];
    }
}
