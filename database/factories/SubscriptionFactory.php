<?php

namespace Database\Factories;

use App\Models\Gym;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        $planName = $this->faker->randomElement(array_keys(Subscription::plans()));

        return [
            'gym_id' => Gym::factory(),
            'user_id' => User::factory()->state(['role' => 'member']),
            'plan_name' => $planName,
            'price' => Subscription::priceForPlan($planName),
            'starts_at' => '2026-07-01',
            'ends_at' => '2026-08-01',
            'status' => 'active',
            'payment_status' => 'paid',
        ];
    }
}
