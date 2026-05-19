<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Gym;
use App\Models\Reservation;
use App\Models\Subscription;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __invoke(): View
    {
        $plans = Gym::selectRaw('subscription_plan, count(*) as aggregate')
            ->groupBy('subscription_plan')
            ->pluck('aggregate', 'subscription_plan');

        $statuses = Gym::selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('super.analytics', [
            'plansChart' => ['labels' => $plans->keys(), 'data' => $plans->values()],
            'statusChart' => ['labels' => $statuses->keys(), 'data' => $statuses->values()],
            'totalReservations' => Reservation::count(),
            'totalCourses' => Course::count(),
            'paidSubscriptions' => Subscription::where('payment_status', 'paid')->sum('price'),
            'topGyms' => Gym::withCount(['members', 'courses', 'reservations'])->orderByDesc('reservations_count')->take(5)->get(),
        ]);
    }
}
