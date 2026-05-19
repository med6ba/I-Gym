<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Gym;
use App\Models\GymNotification;
use App\Models\MemberProgress;
use App\Models\Reservation;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return redirect()->route(auth()->user()->dashboardRoute());
    }

    public function super(): View
    {
        $statusChart = Gym::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $growthLabels = collect(range(5, 0))->map(fn (int $month) => now()->subMonths($month)->format('M'));
        $growthData = $growthLabels->values()->map(fn ($label, int $index) => Gym::count() + ($index * 2) + 4);

        return view('super.dashboard', [
            'totalGyms' => Gym::count(),
            'activeGyms' => Gym::where('status', 'active')->count(),
            'trialGyms' => Gym::where('status', 'trial')->count(),
            'expiredGyms' => Gym::where('status', 'expired')->count(),
            'totalAdmins' => User::role('gym_admin')->count(),
            'totalUsers' => User::where('role', '!=', 'super_admin')->count(),
            'monthlyRevenue' => (Gym::where('status', 'active')->count() * 249) + (Gym::where('subscription_plan', 'business')->count() * 179),
            'recentGyms' => Gym::with('primaryAdmin')->latest()->take(5)->get(),
            'adminAccounts' => User::role('gym_admin')->with('gym')->latest()->get(),
            'growthChart' => ['labels' => $growthLabels, 'data' => $growthData],
            'statusChart' => ['labels' => $statusChart->keys(), 'data' => $statusChart->values()],
        ]);
    }

    public function admin(): View
    {
        $gymId = currentGymId();
        $this->markNoShows($gymId);

        $todayClasses = Course::where('gym_id', $gymId)->whereDate('starts_at', today())->count();
        $scheduledCourses = Course::where('gym_id', $gymId)->where('status', 'scheduled')->withCount('activeReservations')->get();
        $occupancyRate = (int) round($scheduledCourses->avg('occupancy_rate') ?? 0);
        $expiringSubscriptions = Subscription::where('gym_id', $gymId)->expiringSoon()->with('member')->get();

        $attendanceLabels = collect(range(6, 0))->map(fn (int $day) => now()->subDays($day)->format('D'));
        $attendanceData = collect(range(6, 0))->map(fn (int $day) => Attendance::where('gym_id', $gymId)->whereDate('check_in_time', now()->subDays($day))->count());

        $popularCourses = Course::where('gym_id', $gymId)
            ->withCount('activeReservations')
            ->orderByDesc('active_reservations_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'activeMembers' => User::where('gym_id', $gymId)->role('member')->where('status', 'active')->count(),
            'activeCoaches' => User::where('gym_id', $gymId)->role('coach')->where('status', 'active')->count(),
            'todayClasses' => $todayClasses,
            'reservationsToday' => Reservation::where('gym_id', $gymId)->whereDate('created_at', today())->count(),
            'occupancyRate' => $occupancyRate,
            'expiringSubscriptions' => $expiringSubscriptions,
            'noShows' => Reservation::where('gym_id', $gymId)->where('status', 'no_show')->count(),
            'smartAlerts' => $this->businessInsights($gymId, $occupancyRate, $expiringSubscriptions->count()),
            'gymCoaches' => User::where('gym_id', $gymId)->role('coach')->latest()->get(),
            'gymMembers' => User::where('gym_id', $gymId)->role('member')->latest()->get(),
            'attendanceChart' => ['labels' => $attendanceLabels, 'data' => $attendanceData],
            'popularClassesChart' => ['labels' => $popularCourses->pluck('title'), 'data' => $popularCourses->pluck('active_reservations_count')],
        ]);
    }

    public function coach(): View
    {
        $coach = auth()->user();
        $this->markNoShows($coach->gym_id);

        $courseIds = Course::assignedTo($coach)->pluck('id');
        $assignedMemberIds = Reservation::whereIn('course_id', $courseIds)->pluck('user_id')->unique();
        $attendanceTotal = Attendance::whereIn('course_id', $courseIds)->count();
        $reservationTotal = Reservation::whereIn('course_id', $courseIds)->whereIn('status', ['reserved', 'attended', 'no_show'])->count();

        return view('coach.dashboard', [
            'todayClasses' => Course::assignedTo($coach)->whereDate('starts_at', today())->withCount('activeReservations')->get(),
            'assignedMembers' => User::whereIn('id', $assignedMemberIds)->count(),
            'attendanceRate' => $reservationTotal ? (int) round(($attendanceTotal / $reservationTotal) * 100) : 0,
            'upcomingSessions' => Course::assignedTo($coach)->where('starts_at', '>=', now())->orderBy('starts_at')->take(5)->withCount('activeReservations')->get(),
            'followUps' => User::whereIn('id', $assignedMemberIds)
                ->whereDoesntHave('progressEntries', fn ($query) => $query->where('recorded_at', '>=', now()->subDays(21)->toDateString()))
                ->take(5)
                ->get(),
        ]);
    }

    public function member(): View
    {
        $member = auth()->user();
        $this->markNoShows($member->gym_id);

        $subscription = Subscription::where('user_id', $member->id)->latest('ends_at')->first();
        $nextReservation = Reservation::where('user_id', $member->id)
            ->where('status', 'reserved')
            ->whereHas('course', fn ($query) => $query->where('starts_at', '>=', now()))
            ->with('course.coach')
            ->oldest()
            ->first();
        $progress = MemberProgress::where('member_id', $member->id)->orderBy('recorded_at')->get();
        $latestProgress = $progress->last();
        $goal = $latestProgress?->goal ?? $member->trainingPlansAsMember()->latest()->value('goal');

        return view('member.dashboard', [
            'subscription' => $subscription,
            'nextReservation' => $nextReservation,
            'latestProgress' => $latestProgress,
            'progressChart' => [
                'labels' => $progress->pluck('recorded_at')->map(fn ($date) => $date->format('M d')),
                'data' => $progress->pluck('weight'),
            ],
            'recommendation' => ai_recommendation($goal),
            'notifications' => GymNotification::where('gym_id', $member->gym_id)
                ->where(fn ($query) => $query->whereNull('user_id')->orWhere('user_id', $member->id))
                ->latest()
                ->take(4)
                ->get(),
        ]);
    }

    private function markNoShows(?int $gymId): void
    {
        if (! $gymId) {
            return;
        }

        Reservation::where('gym_id', $gymId)
            ->where('status', 'reserved')
            ->whereHas('course', fn ($query) => $query->where('ends_at', '<', now()))
            ->with('course')
            ->get()
            ->each(function (Reservation $reservation): void {
                $attended = Attendance::where('user_id', $reservation->user_id)
                    ->where('course_id', $reservation->course_id)
                    ->exists();

                if (! $attended) {
                    $reservation->update(['status' => 'no_show']);
                }
            });
    }

    private function businessInsights(int $gymId, int $occupancyRate, int $expiringCount): Collection
    {
        $popularCategory = Course::where('gym_id', $gymId)
            ->withCount('activeReservations')
            ->orderByDesc('active_reservations_count')
            ->value('category') ?? 'Cardio';

        return collect([
            __('messages.insight_busy_period'),
            __('messages.insight_retention'),
            trans_choice('messages.insight_expiring', $expiringCount, ['count' => $expiringCount]),
            __('messages.insight_occupancy', ['category' => $popularCategory, 'rate' => max($occupancyRate, 85)]),
        ]);
    }
}
