<?php

namespace Database\Seeders;

use App\Models\GymNotification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $member = User::where('email', 'member@igym.com')->first();
        $admin = User::where('email', 'admin@igym.com')->first();

        if ($member) {
            GymNotification::create([
                'gym_id' => $member->gym_id,
                'user_id' => $member->id,
                'title' => 'Subscription renewal reminder',
                'message' => 'Your current plan is active. Keep your weekly streak alive.',
                'type' => 'warning',
            ]);
        }

        if ($admin) {
            GymNotification::create([
                'gym_id' => $admin->gym_id,
                'user_id' => $admin->id,
                'title' => 'Course occupancy update',
                'message' => 'Strength Training has members reserved for July 6.',
                'type' => 'info',
            ]);
        }
    }
}
