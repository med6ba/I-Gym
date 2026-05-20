<?php

namespace Database\Seeders;

use App\Models\TrainingPlan;
use App\Models\User;
use Illuminate\Database\Seeder;

class TrainingPlanSeeder extends Seeder
{
    public function run(): void
    {
        $coach = User::where('email', 'coach@igym.com')->first();
        $member = User::where('email', 'member@igym.com')->first();

        if ($coach && $member) {
            TrainingPlan::create([
                'gym_id' => $member->gym_id,
                'coach_id' => $coach->id,
                'member_id' => $member->id,
                'title' => 'Fitness 6-week plan',
                'goal' => 'fitness',
                'description' => 'Progressive weekly program with class work and recovery notes.',
                'exercises' => ['Warm-up mobility', 'Main strength block', 'Conditioning finisher'],
            ]);
        }
    }
}
