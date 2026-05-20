<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $gymIds = [1, 2, 3, 4];

        User::factory()->create([
            'name' => 'I-Gym Super Admin',
            'email' => 'super@igym.com',
            'role' => 'super_admin',
            'gym_id' => null,
            'bio' => 'SaaS owner account for managing gym admins.',
        ]);

        $admins = [
            ['name' => 'Amine Atlas', 'email' => 'admin@igym.com', 'gym_id' => 1, 'phone' => '+212 600 10 20 01'],
            ['name' => 'Sofia Tazi', 'email' => 'admin@titan.ma', 'gym_id' => 2, 'phone' => '+212 600 10 20 02'],
            ['name' => 'Younes Berrada', 'email' => 'admin@ironpeak.ma', 'gym_id' => 3, 'phone' => '+212 600 10 20 03'],
            ['name' => 'Leila Chaoui', 'email' => 'admin@flexzone.ma', 'gym_id' => 4, 'phone' => '+212 600 10 20 04'],
        ];

        foreach ($admins as $admin) {
            User::factory()->create(array_merge($admin, [
                'role' => 'gym_admin',
                'bio' => 'Gym admin responsible for members, coaches, courses, and subscriptions.',
            ]));
        }

        $coaches = [
            ['name' => 'Nadia Benali', 'email' => 'coach@igym.com', 'gym_id' => 1, 'phone' => '+212 611 10 00 00', 'gender' => 'female', 'age' => 31, 'height_cm' => 168, 'weight_kg' => 64, 'fitness_goal' => 'fitness'],
            ['name' => 'Karim El Fassi', 'email' => 'coach2@igym.com', 'gym_id' => 1, 'phone' => '+212 611 10 00 01', 'gender' => 'male', 'age' => 35, 'height_cm' => 182, 'weight_kg' => 88, 'fitness_goal' => 'muscle_gain'],
            ['name' => 'Hind Ouazzani', 'email' => 'coach@titan.ma', 'gym_id' => 2, 'phone' => '+212 611 10 00 02', 'gender' => 'female', 'age' => 28, 'height_cm' => 165, 'weight_kg' => 60, 'fitness_goal' => 'endurance'],
            ['name' => 'Driss Amrani', 'email' => 'coach@ironpeak.ma', 'gym_id' => 3, 'phone' => '+212 611 10 00 03', 'gender' => 'male', 'age' => 40, 'height_cm' => 178, 'weight_kg' => 80, 'fitness_goal' => 'fitness'],
        ];

        foreach ($coaches as $coach) {
            User::factory()->create(array_merge($coach, [
                'role' => 'coach',
                'bio' => 'Coach helping members build steady training routines.',
            ]));
        }

        $receptionists = [
            ['name' => 'Samir Reception', 'email' => 'reception@igym.com', 'gym_id' => 1, 'phone' => '+212 612 00 00 01'],
            ['name' => 'Salma Idrissi', 'email' => 'reception@titan.ma', 'gym_id' => 2, 'phone' => '+212 612 00 00 02'],
        ];

        foreach ($receptionists as $receptionist) {
            User::factory()->create(array_merge($receptionist, [
                'role' => 'reception',
                'bio' => 'Front desk receptionist managing bracelet check-ins.',
            ]));
        }

        $members = [
            ['name' => 'Omar Alaoui', 'email' => 'member@igym.com', 'gym_id' => 1, 'phone' => '+212 622 10 00 00', 'gender' => 'male', 'age' => 27, 'height_cm' => 178, 'weight_kg' => 82, 'fitness_goal' => 'fitness'],
            ['name' => 'Lina Berrada', 'email' => 'member2@igym.com', 'gym_id' => 1, 'phone' => '+212 622 10 00 01', 'gender' => 'female', 'age' => 24, 'height_cm' => 163, 'weight_kg' => 58, 'fitness_goal' => 'weight_loss'],
            ['name' => 'Mehdi Idrissi', 'email' => 'member3@igym.com', 'gym_id' => 1, 'phone' => '+212 622 10 00 02', 'gender' => 'male', 'age' => 32, 'height_cm' => 185, 'weight_kg' => 95, 'fitness_goal' => 'muscle_gain'],
            ['name' => 'Yasmine Benali', 'email' => 'member@titan.ma', 'gym_id' => 2, 'phone' => '+212 622 10 00 03', 'gender' => 'female', 'age' => 29, 'height_cm' => 170, 'weight_kg' => 65, 'fitness_goal' => 'endurance'],
            ['name' => 'Anas El Ghali', 'email' => 'member@ironpeak.ma', 'gym_id' => 3, 'phone' => '+212 622 10 00 04', 'gender' => 'male', 'age' => 35, 'height_cm' => 176, 'weight_kg' => 78, 'fitness_goal' => 'fitness'],
        ];

        foreach ($members as $i => $member) {
            User::factory()->create(array_merge($member, [
                'role' => 'member',
                'bracelet_uid' => $i < 3 ? 'NFC-' . str_pad((string) ($i + 1), 8, '0', STR_PAD_LEFT) : null,
                'bio' => 'Member profile prepared for booking, access, and progress tracking.',
            ]));
        }
    }
}
