<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $member = User::where('email', 'member@igym.com')->first();
        if (! $member) return;

        $strength = Course::where('title', 'Strength Training')->first();
        if ($strength) {
            $reservation = Reservation::create([
                'gym_id' => $member->gym_id,
                'user_id' => $member->id,
                'course_id' => $strength->id,
                'status' => 'reserved',
            ]);

            if (function_exists('record_gym_activity')) {
                record_gym_activity($member->gym_id, 'reservation.created', __('messages.log_class_booked', ['course' => $strength->title]), $reservation, $member);
            }
        }

        $yoga = Course::where('title', 'Yoga Flow')->first();
        if ($yoga) {
            Reservation::create([
                'gym_id' => $member->gym_id,
                'user_id' => $member->id,
                'course_id' => $yoga->id,
                'status' => 'attended',
            ]);
        }

        $member2 = User::where('email', 'member2@igym.com')->first();
        if ($member2 && $strength) {
            Reservation::create([
                'gym_id' => $member2->gym_id,
                'user_id' => $member2->id,
                'course_id' => $strength->id,
                'status' => 'reserved',
            ]);
        }
    }
}
