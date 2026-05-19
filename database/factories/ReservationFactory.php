<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gym_id' => Gym::factory(),
            'user_id' => User::factory()->state(['role' => 'member']),
            'course_id' => Course::factory(),
            'status' => $this->faker->randomElement(['reserved', 'cancelled', 'attended', 'no_show']),
        ];
    }
}
