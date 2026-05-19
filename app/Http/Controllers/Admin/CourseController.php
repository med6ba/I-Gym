<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('gym_id', currentGymId())
            ->with(['coach'])
            ->withCount('activeReservations')
            ->latest('starts_at')
            ->paginate(12);

        return view('admin.courses', [
            'courses' => $courses,
            'coaches' => User::where('gym_id', currentGymId())->role('coach')->where('status', 'active')->orderBy('name')->get(),
            'categories' => Course::categoryOptions(currentGymId()),
        ]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeCoach($data['coach_id']);

        $course = Course::create($data + ['gym_id' => currentGymId()]);

        record_gym_activity(currentGymId(), 'course.created', __('messages.log_course_created', ['course' => $course->title]), $course);

        return back()->with('status', __('messages.course_created'));
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);
        $data = $request->validated();
        $this->authorizeCoach($data['coach_id']);
        $course->update($data);

        record_gym_activity(currentGymId(), 'course.updated', __('messages.log_course_updated', ['course' => $course->title]), $course);

        return back()->with('status', __('messages.course_updated'));
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);
        $course->update(['status' => 'cancelled']);

        record_gym_activity(currentGymId(), 'course.cancelled', __('messages.log_course_cancelled', ['course' => $course->title]), $course);

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
