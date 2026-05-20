<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['gym_email' => 'admin@igym.com', 'coach_email' => 'coach@igym.com', 'title' => 'Strength Training', 'category' => 'Strength', 'description' => 'A focused strength class for measurable progress.', 'starts_at' => '2026-07-06 18:00', 'ends_at' => '2026-07-06 19:00', 'max_capacity' => 12, 'room' => 'Studio A'],
            ['gym_email' => 'admin@igym.com', 'coach_email' => 'coach@igym.com', 'title' => 'Cardio Session', 'category' => 'Cardio', 'description' => 'A conditioning class for stamina and recovery.', 'starts_at' => '2026-07-08 18:00', 'ends_at' => '2026-07-08 19:00', 'max_capacity' => 10, 'room' => 'Main Floor'],
            ['gym_email' => 'admin@igym.com', 'coach_email' => 'coach2@igym.com', 'title' => 'Yoga Flow', 'category' => 'Yoga', 'description' => 'Slow-flow vinyasa for mobility and relaxation.', 'starts_at' => '2026-07-07 07:00', 'ends_at' => '2026-07-07 08:00', 'max_capacity' => 15, 'room' => 'Studio B'],
            ['gym_email' => 'admin@igym.com', 'coach_email' => 'coach2@igym.com', 'title' => 'HIIT Blast', 'category' => 'Crossfit', 'description' => 'High-intensity interval training for maximum calorie burn.', 'starts_at' => '2026-07-10 17:00', 'ends_at' => '2026-07-10 18:00', 'max_capacity' => 8, 'room' => 'Studio A'],
            ['gym_email' => 'admin@titan.ma', 'coach_email' => 'coach@titan.ma', 'title' => 'Morning Pilates', 'category' => 'Pilates', 'description' => 'Core-strengthening pilates for all levels.', 'starts_at' => '2026-07-06 08:00', 'ends_at' => '2026-07-06 09:00', 'max_capacity' => 10, 'room' => 'Studio 1'],
            ['gym_email' => 'admin@titan.ma', 'coach_email' => 'coach@titan.ma', 'title' => 'Spinning', 'category' => 'Cardio', 'description' => 'High-energy indoor cycling session.', 'starts_at' => '2026-07-09 18:00', 'ends_at' => '2026-07-09 19:00', 'max_capacity' => 6, 'room' => 'Cycle Room'],
            ['gym_email' => 'admin@ironpeak.ma', 'coach_email' => 'coach@ironpeak.ma', 'title' => 'CrossFit Challenge', 'category' => 'Crossfit', 'description' => 'Functional fitness WOD for experienced athletes.', 'starts_at' => '2026-07-07 18:00', 'ends_at' => '2026-07-07 19:30', 'max_capacity' => 6, 'room' => 'Box'],
        ];

        foreach ($courses as $course) {
            $gym = \App\Models\User::where('email', $course['gym_email'])->first()?->gym;
            if (! $gym) continue;

            Course::create([
                'gym_id' => $gym->id,
                'coach_id' => \App\Models\User::where('email', $course['coach_email'])->first()?->id,
                'title' => $course['title'],
                'category' => $course['category'],
                'description' => $course['description'],
                'starts_at' => Carbon::parse($course['starts_at']),
                'ends_at' => Carbon::parse($course['ends_at']),
                'max_capacity' => $course['max_capacity'],
                'room' => $course['room'],
                'status' => 'scheduled',
            ]);
        }
    }
}
