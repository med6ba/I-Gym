<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GymUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        return view('admin.members', [
            'members' => User::where('gym_id', currentGymId())->role('member')->with('activeSubscription')->latest()->paginate(12),
        ]);
    }

    public function store(GymUserRequest $request): RedirectResponse
    {
        User::create($request->safe()->merge([
            'gym_id' => currentGymId(),
            'role' => 'member',
            'language' => app()->getLocale(),
            'theme' => 'light',
        ])->all());

        return back()->with('status', __('messages.member_created'));
    }

    public function update(GymUserRequest $request, User $member): RedirectResponse
    {
        $this->authorizeMember($member);
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $member->update($data);

        return back()->with('status', __('messages.member_updated'));
    }

    public function destroy(User $member): RedirectResponse
    {
        $this->authorizeMember($member);
        $member->delete();

        return back()->with('status', __('messages.member_deleted'));
    }

    private function authorizeMember(User $member): void
    {
        abort_unless($member->gym_id === currentGymId() && $member->role === 'member', 403);
    }
}
