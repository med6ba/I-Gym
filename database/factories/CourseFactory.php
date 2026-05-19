<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $category = $this->faker->randomElement(Course::DEFAULT_CATEGORIES);
        $start = $this->faker->dateTimeBetween('2026-07-01', '2026-12-31');

        return [
            'gym_id' => Gym::factory(),
            'coach_id' => User::factory()->state(['role' => 'coach']),
            'title' => $category.' Session',
            'category' => $category,
            'description' => $this->faker->sentence(14),
            'starts_at' => $start,
            'ends_at' => (clone $start)->modify('+60 minutes'),
            'max_capacity' => $this->faker->numberBetween(8, 24),
            'room' => $this->faker->randomElement(['Studio A', 'Studio B', 'Main Floor']),
            'status' => 'scheduled',
        ];
    }
}
