<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Gym;
use App\Models\GymNotification;
use App\Models\MemberProgress;
use App\Models\Reservation;
use App\Models\Subscription;
use App\Models\TrainingPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'I-Gym Super Admin',
            'email' => 'super@igym.com',
            'role' => 'super_admin',
            'gym_id' => null,
            'password' => 'password',
            'status' => 'active',
            'language' => 'en',
            'theme' => 'light',
            'currency' => 'MAD',
            'bio' => 'SaaS owner account for managing gym admins.',
        ]);

        $gym = Gym::create([
            'name' => 'Atlas Fitness Club',
            'slug' => Str::slug('Atlas Fitness Club'),
            'phone' => '+212 522 100 100',
            'address' => '12 Avenue Mohammed V',
            'city' => 'Casablanca',
            'status' => 'active',
            'subscription_plan' => 'business',
            'subscription_started_at' => Carbon::parse('2026-07-01'),
            'subscription_ends_at' => Carbon::parse('2027-07-01'),
        ]);

        $admin = User::create([
            'gym_id' => $gym->id,
            'name' => 'Amine Atlas',
            'email' => 'admin@igym.com',
            'password' => 'password',
            'role' => 'gym_admin',
            'phone' => '+212 600 10 20 01',
            'status' => 'active',
            'language' => 'en',
            'theme' => 'light',
            'currency' => 'MAD',
            'bio' => 'Gym admin responsible for members, coaches, courses, and subscriptions.',
        ]);

        User::create([
            'gym_id' => $gym->id,
            'name' => 'Samir Reception',
            'email' => 'reception@igym.com',
            'password' => 'password',
            'role' => 'reception',
            'phone' => '+212 612 00 00 01',
            'status' => 'active',
            'language' => 'en',
            'theme' => 'light',
            'currency' => 'MAD',
            'bio' => 'Front desk receptionist managing bracelet check-ins.',
        ]);

        $coach = User::create([
            'gym_id' => $gym->id,
            'name' => 'Nadia Benali',
            'email' => 'coach@igym.com',
            'password' => 'password',
            'role' => 'coach',
            'phone' => '+212 611 10 00 00',
            'status' => 'active',
            'language' => 'en',
            'theme' => 'light',
            'currency' => 'MAD',
            'age' => 31,
            'height_cm' => 168,
            'weight_kg' => 64,
            'gender' => 'female',
            'fitness_goal' => 'fitness',
            'bio' => 'Coach helping members build steady training routines.',
        ]);

        $member = User::create([
            'gym_id' => $gym->id,
            'name' => 'Omar Alaoui',
            'email' => 'member@igym.com',
            'password' => 'password',
            'role' => 'member',
            'phone' => '+212 622 10 00 00',
            'status' => 'active',
            'language' => 'en',
            'theme' => 'light',
            'currency' => 'MAD',
            'age' => 27,
            'height_cm' => 178,
            'weight_kg' => 82,
            'gender' => 'male',
            'fitness_goal' => 'fitness',
            'bio' => 'Member profile prepared for booking, access, and progress tracking.',
        ]);

        foreach (Subscription::plans() as $planName => $plan) {
            Subscription::create([
                'gym_id' => $gym->id,
                'user_id' => $member->id,
                'plan_name' => $planName,
                'price' => $plan['price'],
                'starts_at' => $planName === Subscription::PLAN_PRIMARY ? '2026-07-01' : '2026-08-01',
                'ends_at' => $planName === Subscription::PLAN_PRIMARY ? '2026-07-31' : '2026-08-31',
                'status' => 'active',
                'payment_status' => 'paid',
            ]);
        }

        $strengthClass = Course::create([
            'gym_id' => $gym->id,
            'coach_id' => $coach->id,
            'title' => 'Strength Training',
            'category' => 'Strength',
            'description' => 'A focused strength class for measurable progress.',
            'starts_at' => Carbon::parse('2026-07-06 18:00'),
            'ends_at' => Carbon::parse('2026-07-06 19:00'),
            'max_capacity' => 12,
            'room' => 'Studio A',
            'status' => 'scheduled',
        ]);

        Course::create([
            'gym_id' => $gym->id,
            'coach_id' => $coach->id,
            'title' => 'Cardio Session',
            'category' => 'Cardio',
            'description' => 'A conditioning class for stamina and recovery.',
            'starts_at' => Carbon::parse('2026-07-08 18:00'),
            'ends_at' => Carbon::parse('2026-07-08 19:00'),
            'max_capacity' => 10,
            'room' => 'Main Floor',
            'status' => 'scheduled',
        ]);

        $reservation = Reservation::create([
            'gym_id' => $gym->id,
            'user_id' => $member->id,
            'course_id' => $strengthClass->id,
            'status' => 'reserved',
        ]);

        Attendance::create([
            'gym_id' => $gym->id,
            'user_id' => $member->id,
            'course_id' => $strengthClass->id,
            'checked_in_by' => $coach->id,
            'check_in_time' => Carbon::parse('2026-07-06 18:05'),
            'method' => 'qr',
        ]);

        TrainingPlan::create([
            'gym_id' => $gym->id,
            'coach_id' => $coach->id,
            'member_id' => $member->id,
            'title' => 'Fitness 6-week plan',
            'goal' => 'fitness',
            'description' => 'Progressive weekly program with class work and recovery notes.',
            'exercises' => ['Warm-up mobility', 'Main strength block', 'Conditioning finisher'],
        ]);

        foreach (['2026-07-01', '2026-07-08', '2026-07-15'] as $index => $date) {
            MemberProgress::create([
                'gym_id' => $gym->id,
                'member_id' => $member->id,
                'weight' => 82 - ($index * 0.8),
                'body_fat' => 24 - ($index * 0.3),
                'muscle_mass' => 31 + ($index * 0.2),
                'goal' => 'fitness',
                'notes' => 'Consistent training week with good recovery.',
                'recorded_at' => $date,
            ]);
        }

        GymNotification::create([
            'gym_id' => $gym->id,
            'user_id' => $member->id,
            'title' => 'Subscription renewal reminder',
            'message' => 'Your current plan is active. Keep your weekly streak alive.',
            'type' => 'warning',
        ]);

        GymNotification::create([
            'gym_id' => $gym->id,
            'user_id' => $admin->id,
            'title' => 'Course occupancy update',
            'message' => 'Strength Training has one member reserved for July 6.',
            'type' => 'info',
        ]);

        record_gym_activity($gym->id, 'reservation.created', __('messages.log_class_booked', ['course' => $strengthClass->title]), $reservation, $member);
    }
}
