<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $members = User::where('role', 'member')->get();
        $plans = Subscription::plans();

        foreach ($members as $member) {
            Subscription::create([
                'gym_id' => $member->gym_id,
                'user_id' => $member->id,
                'plan_name' => Subscription::PLAN_PRIMARY,
                'price' => $plans[Subscription::PLAN_PRIMARY]['price'],
                'starts_at' => '2026-07-01',
                'ends_at' => '2026-07-31',
                'status' => 'active',
                'payment_status' => 'paid',
            ]);

            if ($member->email === 'member@igym.com') {
                Subscription::create([
                    'gym_id' => $member->gym_id,
                    'user_id' => $member->id,
                    'plan_name' => Subscription::PLAN_PERSONAL_COACHING,
                    'price' => $plans[Subscription::PLAN_PERSONAL_COACHING]['price'],
                    'starts_at' => '2026-08-01',
                    'ends_at' => '2026-08-31',
                    'status' => 'active',
                    'payment_status' => 'paid',
                ]);
            }
        }
    }
}
