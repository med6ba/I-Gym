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
            'email' => 'super@igym.test',
            'role' => 'super_admin',
            'gym_id' => null,
            'password' => 'password',
            'status' => 'active',
        ]);

        $gyms = collect([
            [
                'name' => 'Atlas Fitness Club',
                'email' => 'contact@atlasfitness.test',
                'phone' => '+212 522 100 100',
                'address' => '12 Avenue Mohammed V',
                'city' => 'Casablanca',
                'status' => 'active',
                'subscription_plan' => 'business',
            ],
            [
                'name' => 'Orange Gym Center',
                'email' => 'hello@orangegym.test',
                'phone' => '+212 537 200 200',
                'address' => '44 Rue Agdal',
                'city' => 'Rabat',
                'status' => 'trial',
                'subscription_plan' => 'pro',
            ],
            [
                'name' => 'PowerHouse Rabat',
                'email' => 'team@powerhouserabat.test',
                'phone' => '+212 535 300 300',
                'address' => '7 Boulevard Annakhil',
                'city' => 'Rabat',
                'status' => 'expired',
                'subscription_plan' => 'basic',
            ],
        ])->map(fn (array $gym) => Gym::create($gym + [
            'slug' => Str::slug($gym['name']),
            'subscription_started_at' => now()->subMonths(3),
            'subscription_ends_at' => $gym['status'] === 'expired' ? now()->subDays(3) : now()->addMonths(2),
        ]));

        $categories = ['Crossfit', 'Yoga', 'Cardio', 'Strength', 'Boxing', 'Pilates'];
        $rooms = ['Studio A', 'Studio B', 'Main Floor', 'Ring', 'Zen Room'];

        $gyms->each(function (Gym $gym, int $gymIndex) use ($categories, $rooms): void {
            $adminEmail = $gymIndex === 0 ? 'admin@igym.test' : 'admin'.($gymIndex + 1).'@igym.test';
            $admin = User::create([
                'gym_id' => $gym->id,
                'name' => $gym->name.' Admin',
                'email' => $adminEmail,
                'password' => 'password',
                'role' => 'gym_admin',
                'phone' => '+212 600 10 20 '.($gymIndex + 1),
                'status' => 'active',
            ]);

            $coaches = collect([
                ['name' => 'Nadia Benali', 'focus' => 'Yoga'],
                ['name' => 'Youssef El Amrani', 'focus' => 'Strength'],
                ['name' => 'Sara Mansouri', 'focus' => 'Cardio'],
            ])->map(function (array $coach, int $index) use ($gym, $gymIndex): User {
                return User::create([
                    'gym_id' => $gym->id,
                    'name' => $coach['name'],
                    'email' => $gymIndex === 0 && $index === 0 ? 'coach@igym.test' : 'coach'.($gymIndex + 1).($index + 1).'@igym.test',
                    'password' => 'password',
                    'role' => 'coach',
                    'phone' => '+212 611 '.($gymIndex + 1).$index.' 00 00',
                    'status' => 'active',
                ]);
            });

            $members = collect([
                'Omar Alaoui',
                'Lina Berrada',
                'Mehdi Idrissi',
                'Ines Tazi',
                'Adam Fassi',
                'Salma Hilali',
                'Karim Saidi',
                'Maya Chraibi',
            ])->map(function (string $name, int $index) use ($gym, $gymIndex): User {
                return User::create([
                    'gym_id' => $gym->id,
                    'name' => $name,
                    'email' => $gymIndex === 0 && $index === 0 ? 'member@igym.test' : 'member'.($gymIndex + 1).($index + 1).'@igym.test',
                    'password' => 'password',
                    'role' => 'member',
                    'phone' => '+212 622 '.($gymIndex + 1).$index.' 00 00',
                    'status' => $index === 7 ? 'inactive' : 'active',
                ]);
            });

            $members->each(function (User $member, int $index) use ($gym): void {
                $endsAt = match (true) {
                    $index === 2 => now()->addDays(4),
                    $index === 5 => now()->subDays(8),
                    default => now()->addDays(30 + ($index * 4)),
                };

                Subscription::create([
                    'gym_id' => $gym->id,
                    'user_id' => $member->id,
                    'plan_name' => $index % 3 === 0 ? 'Premium Coaching' : 'Monthly Access',
                    'price' => $index % 3 === 0 ? 599 : 299,
                    'starts_at' => now()->subMonth()->toDateString(),
                    'ends_at' => $endsAt->toDateString(),
                    'status' => $endsAt->isPast() ? 'expired' : 'active',
                    'payment_status' => $index === 4 ? 'unpaid' : 'paid',
                ]);
            });

            $courses = collect(range(0, 11))->map(function (int $index) use ($gym, $coaches, $categories, $rooms): Course {
                $start = now()->subDays(3)->addDays($index)->setTime(18 + ($index % 3), 0);
                $category = $categories[$index % count($categories)];

                return Course::create([
                    'gym_id' => $gym->id,
                    'coach_id' => $coaches[$index % $coaches->count()]->id,
                    'title' => $category === 'Strength' ? 'Strength Training' : $category.' Session',
                    'category' => $category,
                    'description' => 'A focused '.$category.' class designed for measurable progress and strong member engagement.',
                    'starts_at' => $start,
                    'ends_at' => (clone $start)->addMinutes(60),
                    'max_capacity' => $index === 5 ? 4 : ($index === 6 ? 5 : 12),
                    'room' => $rooms[$index % count($rooms)],
                    'status' => $index === 1 ? 'completed' : 'scheduled',
                ]);
            });

            $courses->each(function (Course $course, int $courseIndex) use ($gym, $members): void {
                $bookingLimit = match ($courseIndex) {
                    5 => 4,
                    6 => 4,
                    default => min($members->count(), 2 + ($courseIndex % 5)),
                };
                $bookingMembers = $courseIndex === 3 ? $members : $members->slice(1)->values();

                $bookingMembers->take($bookingLimit)->each(function (User $member, int $memberIndex) use ($gym, $course): void {
                    $status = $course->ends_at->isPast()
                        ? ($memberIndex % 3 === 0 ? 'no_show' : 'attended')
                        : 'reserved';

                    Reservation::create([
                        'gym_id' => $gym->id,
                        'user_id' => $member->id,
                        'course_id' => $course->id,
                        'status' => $status,
                    ]);

                    if ($status === 'attended') {
                        Attendance::create([
                            'gym_id' => $gym->id,
                            'user_id' => $member->id,
                            'course_id' => $course->id,
                            'checked_in_by' => $course->coach_id,
                            'check_in_time' => Carbon::parse($course->starts_at)->addMinutes(5),
                            'method' => $memberIndex % 2 === 0 ? 'qr' : 'manual',
                        ]);
                    }
                });
            });

            $members->take(5)->each(function (User $member, int $index) use ($gym, $coaches): void {
                $goal = ['weight_loss', 'muscle_gain', 'fitness', 'endurance', 'weight_loss'][$index];

                TrainingPlan::create([
                    'gym_id' => $gym->id,
                    'coach_id' => $coaches[$index % $coaches->count()]->id,
                    'member_id' => $member->id,
                    'title' => Str::headline($goal).' 6-week plan',
                    'goal' => $goal,
                    'description' => 'Progressive weekly program with smart class mix and recovery notes.',
                    'exercises' => ['Warm-up mobility', 'Main strength block', 'Conditioning finisher'],
                ]);

                collect(range(5, 0))->each(function (int $week) use ($gym, $member, $goal, $index): void {
                    MemberProgress::create([
                        'gym_id' => $gym->id,
                        'member_id' => $member->id,
                        'weight' => 82 - ($index * 2) - ((5 - $week) * 0.7),
                        'body_fat' => 24 - ((5 - $week) * 0.4),
                        'muscle_mass' => 31 + ((5 - $week) * 0.3),
                        'goal' => $goal,
                        'notes' => 'Consistent training week with good recovery.',
                        'recorded_at' => now()->subWeeks($week)->toDateString(),
                    ]);
                });
            });

            GymNotification::create([
                'gym_id' => $gym->id,
                'user_id' => $members->first()->id,
                'title' => 'Subscription renewal reminder',
                'message' => 'Your current plan is active. Keep your weekly streak alive.',
                'type' => 'warning',
            ]);

            GymNotification::create([
                'gym_id' => $gym->id,
                'user_id' => null,
                'title' => 'High occupancy alert',
                'message' => 'Cardio has reached 85% average occupancy this week.',
                'type' => 'info',
            ]);

            GymNotification::create([
                'gym_id' => $gym->id,
                'user_id' => $admin->id,
                'title' => 'Smart business insight',
                'message' => 'Wednesday evenings are your busiest period.',
                'type' => 'success',
            ]);
        });
    }
}
