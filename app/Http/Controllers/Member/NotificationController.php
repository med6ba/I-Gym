<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\GymNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('member.notifications', [
            'notifications' => GymNotification::where('gym_id', auth()->user()->gym_id)
                ->where(fn ($query) => $query->whereNull('user_id')->orWhere('user_id', auth()->id()))
                ->latest()
                ->paginate(14),
        ]);
    }

    public function markRead(GymNotification $notification): RedirectResponse
    {
        abort_unless($notification->gym_id === auth()->user()->gym_id && $notification->user_id === auth()->id(), 403);

        $notification->update(['is_read' => true]);

        return back()->with('status', __('messages.notification_read'));
    }
}
