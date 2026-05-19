<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RecordAttendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        return view('admin.attendance', [
            'attendances' => Attendance::where('gym_id', currentGymId())->with(['member', 'course', 'checkedInBy'])->latest('check_in_time')->paginate(14),
            'members' => User::where('gym_id', currentGymId())->role('member')->orderBy('name')->get(),
            'courses' => Course::where('gym_id', currentGymId())->where('status', 'scheduled')->orderBy('starts_at')->get(),
        ]);
    }

    public function store(AttendanceRequest $request, RecordAttendance $recorder): RedirectResponse
    {
        $member = User::findOrFail($request->integer('member_id'));
        $course = $request->filled('course_id') ? Course::findOrFail($request->integer('course_id')) : null;

        $recorder->handle(auth()->user(), $member, $course, $request->input('method', 'manual'));

        return back()->with('status', __('messages.attendance_recorded'));
    }
}
