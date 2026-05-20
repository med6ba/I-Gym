<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GymSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            SubscriptionSeeder::class,
            ReservationSeeder::class,
            AttendanceSeeder::class,
            TrainingPlanSeeder::class,
            ProgressSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
