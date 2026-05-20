<?php

namespace App\Http\Controllers\Reception;

use App\Actions\RecordAttendance;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ScannerController extends Controller
{
    public function index(): View
    {
        return view('reception.scanner', [
            'members' => User::where('gym_id', currentGymId())->role('member')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, RecordAttendance $recorder): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:users,id'],
            'method' => ['required', Rule::in(['nfc', 'manual'])],
        ]);

        $member = User::findOrFail($validated['member_id']);

        $recorder->handle(auth()->user(), $member, null, $validated['method']);

        record_gym_activity(currentGymId(), 'attendance.recorded', __('messages.log_attendance_recorded', ['member' => $member->name]));

        return back()->with('status', __('messages.attendance_recorded'));
    }
}
