<?php

namespace App\Http\Controllers\Coach;

use App\Actions\RecordAttendance;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function index(Request $request): View
    {
        return view('coach.classes', [
            'courses' => Course::assignedTo(auth()->user())
                ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
                ->withCount('activeReservations')
                ->orderBy('starts_at')
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function attendance(Course $course): View
    {
        $this->authorizeCourse($course);

        return view('coach.class-attendance', [
            'course' => $course->load(['coach', 'reservations.member', 'attendances.member']),
            'members' => $course->reservations()->with('member')->get()->pluck('member')->filter(),
        ]);
    }

    public function markAttendance(Request $request, Course $course, RecordAttendance $recorder): RedirectResponse
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'member_id' => ['required', 'exists:users,id'],
            'method' => ['required', Rule::in(['qr', 'manual'])],
        ]);

        $member = User::findOrFail($validated['member_id']);
        $recorder->handle(auth()->user(), $member, $course, $validated['method']);

        return back()->with('status', __('messages.attendance_recorded'));
    }

    private function authorizeCourse(Course $course): void
    {
        abort_unless($course->gym_id === auth()->user()->gym_id && $course->coach_id === auth()->id(), 403);
    }
}
