<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GymUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CoachController extends Controller
{
    public function index(): View
    {
        return view('admin.coaches', [
            'coaches' => User::where('gym_id', currentGymId())->role('coach')->withCount('coachedCourses')->latest()->paginate(12),
        ]);
    }

    public function store(GymUserRequest $request): RedirectResponse
    {
        $coach = User::create($request->safe()->merge([
            'gym_id' => currentGymId(),
            'role' => 'coach',
            'language' => app()->getLocale(),
            'theme' => 'light',
        ])->all());

        record_gym_activity(currentGymId(), 'coach.created', __('messages.log_coach_created', ['coach' => $coach->name]), $coach);

        return back()->with('status', __('messages.coach_created'));
    }

    public function update(GymUserRequest $request, User $coach): RedirectResponse
    {
        $this->authorizeCoach($coach);
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $coach->update($data);

        record_gym_activity(currentGymId(), 'coach.updated', __('messages.log_coach_updated', ['coach' => $coach->name]), $coach);

        return back()->with('status', __('messages.coach_updated'));
    }

    public function destroy(User $coach): RedirectResponse
    {
        $this->authorizeCoach($coach);
        $name = $coach->name;
        $coach->delete();

        record_gym_activity(currentGymId(), 'coach.deleted', __('messages.log_coach_deleted', ['coach' => $name]));

        return back()->with('status', __('messages.coach_deleted'));
    }

    private function authorizeCoach(User $coach): void
    {
        abort_unless($coach->gym_id === currentGymId() && $coach->role === 'coach', 403);
    }
}
