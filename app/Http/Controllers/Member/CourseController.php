<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Reservation;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $member = auth()->user();
        $courses = Course::where('gym_id', $member->gym_id)
            ->where('status', 'scheduled')
            ->where('starts_at', '>=', now())
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            ->with('coach')
            ->withCount('activeReservations')
            ->orderBy('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('member.courses', [
            'courses' => $courses,
            'categories' => ['Crossfit', 'Yoga', 'Cardio', 'Strength', 'Boxing', 'Pilates'],
            'reservedCourseIds' => Reservation::where('user_id', $member->id)->where('status', 'reserved')->pluck('course_id')->all(),
            'hasActiveSubscription' => Subscription::where('user_id', $member->id)
                ->where('status', 'active')
                ->whereDate('ends_at', '>=', today())
                ->exists(),
        ]);
    }

    public function reserve(Course $course): RedirectResponse
    {
        $member = auth()->user();

        abort_unless($course->gym_id === $member->gym_id, 403);

        if ($course->status !== 'scheduled' || $course->starts_at->isPast()) {
            return back()->withErrors(['course' => __('messages.course_not_bookable')]);
        }

        $hasActiveSubscription = Subscription::where('user_id', $member->id)
            ->where('status', 'active')
            ->whereDate('ends_at', '>=', today())
            ->exists();

        if (! $hasActiveSubscription) {
            return back()->withErrors(['course' => __('messages.subscription_required')]);
        }

        if ($course->activeReservations()->count() >= $course->max_capacity) {
            return back()->withErrors(['course' => __('messages.class_full')]);
        }

        if (Reservation::where('user_id', $member->id)->where('course_id', $course->id)->exists()) {
            return back()->withErrors(['course' => __('messages.already_reserved')]);
        }

        Reservation::create([
            'gym_id' => $member->gym_id,
            'user_id' => $member->id,
            'course_id' => $course->id,
            'status' => 'reserved',
        ]);

        return back()->with('status', __('messages.class_booked'));
    }
}
