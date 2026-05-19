<?php

namespace Database\Factories;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('now', '+30 days');

        return [
            'gym_id' => Gym::factory(),
            'coach_id' => User::factory()->state(['role' => 'coach']),
            'title' => $this->faker->randomElement(['Crossfit Blast', 'Yoga Flow', 'Cardio Burn', 'Strength Training']),
            'category' => $this->faker->randomElement(['Crossfit', 'Yoga', 'Cardio', 'Strength', 'Boxing', 'Pilates']),
            'description' => $this->faker->sentence(14),
            'starts_at' => $start,
            'ends_at' => (clone $start)->modify('+60 minutes'),
            'max_capacity' => $this->faker->numberBetween(8, 24),
            'room' => $this->faker->randomElement(['Studio A', 'Studio B', 'Main Floor']),
            'status' => 'scheduled',
        ];
    }
}
