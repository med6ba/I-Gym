<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::where('gym_id', currentGymId())
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            ->with(['coach'])
            ->withCount('activeReservations')
            ->latest('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.courses', [
            'courses' => $courses,
            'coaches' => User::where('gym_id', currentGymId())->role('coach')->where('status', 'active')->orderBy('name')->get(),
            'categories' => ['Crossfit', 'Yoga', 'Cardio', 'Strength', 'Boxing', 'Pilates'],
        ]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeCoach($data['coach_id']);

        Course::create($data + ['gym_id' => currentGymId()]);

        return back()->with('status', __('messages.course_created'));
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);
        $data = $request->validated();
        $this->authorizeCoach($data['coach_id']);
        $course->update($data);

        return back()->with('status', __('messages.course_updated'));
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);
        $course->update(['status' => 'cancelled']);

        return back()->with('status', __('messages.course_cancelled'));
    }

    private function authorizeCourse(Course $course): void
    {
        abort_unless($course->gym_id === currentGymId(), 403);
    }

    private function authorizeCoach(int $coachId): void
    {
        abort_unless(User::where('gym_id', currentGymId())->role('coach')->whereKey($coachId)->exists(), 403);
    }
}
