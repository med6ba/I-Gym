<?php

namespace Database\Seeders;

use App\Models\MemberProgress;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProgressSeeder extends Seeder
{
    public function run(): void
    {
        $member = User::where('email', 'member@igym.com')->first();
        if (! $member) return;

        foreach (['2026-07-01', '2026-07-08', '2026-07-15'] as $index => $date) {
            MemberProgress::create([
                'gym_id' => $member->gym_id,
                'member_id' => $member->id,
                'weight' => 82 - ($index * 0.8),
                'body_fat' => 24 - ($index * 0.3),
                'muscle_mass' => 31 + ($index * 0.2),
                'goal' => 'fitness',
                'notes' => 'Consistent training week with good recovery.',
                'recorded_at' => $date,
            ]);
        }

        $member2 = User::where('email', 'member2@igym.com')->first();
        if ($member2) {
            MemberProgress::create([
                'gym_id' => $member2->gym_id,
                'member_id' => $member2->id,
                'weight' => 58,
                'body_fat' => 22,
                'muscle_mass' => 26,
                'goal' => 'weight_loss',
                'notes' => 'Starting measurements for the weight loss program.',
                'recorded_at' => '2026-07-01',
            ]);
        }
    }
}
