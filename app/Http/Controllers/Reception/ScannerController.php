<?php

namespace App\Http\Controllers\Reception;

use App\Actions\RecordAttendance;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\GymActivityLog;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScannerController extends Controller
{
    public function index(): View
    {
        return view('reception.scanner', [
            'recentScans' => GymActivityLog::where('gym_id', auth()->user()->gym_id)
                ->where('action', 'attendance.qr_scanned')
                ->with('actor')
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }

    public function scan(Request $request, RecordAttendance $recorder): RedirectResponse
    {
        $validated = $request->validate([
            'payload' => ['required', 'string', 'max:255'],
        ]);

        if (! preg_match('/^IGYM\|member:(\d+)\|gym:(\d+)\|issued:(\d+)$/', trim($validated['payload']), $matches)) {
            return back()->withErrors(['payload' => __('messages.invalid_qr_payload')]);
        }

        $memberId = (int) $matches[1];
        $gymId = (int) $matches[2];
        $issuedAt = (int) $matches[3];
        $reception = $request->user();

        if ($gymId !== $reception->gym_id) {
            return back()->withErrors(['payload' => __('messages.qr_wrong_gym')]);
        }

        if ($issuedAt < now()->subHours(12)->timestamp) {
            return back()->withErrors(['payload' => __('messages.qr_expired')]);
        }

        $member = User::where('gym_id', $reception->gym_id)
            ->where('role', 'member')
            ->where('status', 'active')
            ->find($memberId);

        if (! $member) {
            return back()->withErrors(['payload' => __('messages.member_not_allowed')]);
        }

        $hasActiveSubscription = Subscription::where('user_id', $member->id)
            ->where('status', 'active')
            ->whereDate('ends_at', '>=', today())
            ->exists();

        if (! $hasActiveSubscription) {
            return back()->withErrors(['payload' => __('messages.subscription_required')]);
        }

        $alreadyCheckedIn = Attendance::where('gym_id', $reception->gym_id)
            ->where('user_id', $member->id)
            ->whereNull('course_id')
            ->whereDate('check_in_time', today())
            ->exists();

        if ($alreadyCheckedIn) {
            return back()->withErrors(['payload' => __('messages.already_checked_in_today')]);
        }

        $attendance = $recorder->handle($reception, $member, null, 'qr');

        record_gym_activity(
            $reception->gym_id,
            'attendance.qr_scanned',
            __('messages.log_qr_scanned', ['member' => $member->name]),
            $attendance,
            $reception,
            ['member_id' => $member->id, 'payload_issued_at' => $issuedAt]
        );

        return back()->with('status', __('messages.qr_scan_success', ['member' => $member->name]));
    }
}
