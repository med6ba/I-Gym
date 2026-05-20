<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $member = User::where('email', 'member@igym.com')->first();
        $coach = User::where('email', 'coach@igym.com')->first();
        $strength = Course::where('title', 'Strength Training')->first();

        if ($member && $coach && $strength) {
            Attendance::create([
                'gym_id' => $member->gym_id,
                'user_id' => $member->id,
                'course_id' => $strength->id,
                'checked_in_by' => $coach->id,
                'check_in_time' => Carbon::parse('2026-07-06 18:05'),
                'method' => 'nfc',
            ]);
        }

        $yoga = Course::where('title', 'Yoga Flow')->first();
        if ($member && $coach && $yoga) {
            Attendance::create([
                'gym_id' => $member->gym_id,
                'user_id' => $member->id,
                'course_id' => $yoga->id,
                'checked_in_by' => $coach->id,
                'check_in_time' => Carbon::parse('2026-07-07 07:02'),
                'method' => 'nfc',
            ]);
        }
    }
}
