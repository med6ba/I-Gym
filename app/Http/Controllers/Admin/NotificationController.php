<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationRequest;
use App\Models\GymNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('admin.notifications', [
            'notifications' => GymNotification::where('gym_id', currentGymId())->with('user')->latest()->paginate(14),
            'members' => User::where('gym_id', currentGymId())->role('member')->orderBy('name')->get(),
        ]);
    }

    public function store(NotificationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['user_id'])) {
            abort_unless(User::where('gym_id', currentGymId())->whereKey($data['user_id'])->exists(), 403);
        }

        GymNotification::create($data + ['gym_id' => currentGymId(), 'is_read' => false]);

        return back()->with('status', __('messages.notification_sent'));
    }
}
