<?php

namespace Database\Seeders;

use App\Models\Gym;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class GymSeeder extends Seeder
{
    public function run(): void
    {
        Gym::create([
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

        Gym::create([
            'name' => 'Titan Gym Rabat',
            'slug' => Str::slug('Titan Gym Rabat'),
            'phone' => '+212 537 200 200',
            'address' => '45 Rue Oued Souss',
            'city' => 'Rabat',
            'status' => 'active',
            'subscription_plan' => 'pro',
            'subscription_started_at' => Carbon::parse('2026-06-01'),
            'subscription_ends_at' => Carbon::parse('2027-06-01'),
        ]);

        Gym::create([
            'name' => 'Iron Peak Marrakech',
            'slug' => Str::slug('Iron Peak Marrakech'),
            'phone' => '+212 524 300 300',
            'address' => '8 Rue de la Liberté',
            'city' => 'Marrakech',
            'status' => 'trial',
            'subscription_plan' => 'basic',
            'subscription_started_at' => Carbon::parse('2026-09-01'),
            'subscription_ends_at' => Carbon::parse('2026-10-01'),
        ]);

        Gym::create([
            'name' => 'FlexZone Tangier',
            'slug' => Str::slug('FlexZone Tangier'),
            'phone' => '+212 539 400 400',
            'address' => '22 Boulevard Pasteur',
            'city' => 'Tangier',
            'status' => 'suspended',
            'subscription_plan' => 'pro',
            'subscription_started_at' => Carbon::parse('2025-01-01'),
            'subscription_ends_at' => Carbon::parse('2026-01-01'),
        ]);
    }
}
