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
        $member = User::create($request->safe()->merge([
            'gym_id' => currentGymId(),
            'role' => 'member',
            'language' => app()->getLocale(),
            'theme' => 'light',
        ])->all());

        record_gym_activity(currentGymId(), 'member.created', __('messages.log_member_created', ['member' => $member->name]), $member);

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

        record_gym_activity(currentGymId(), 'member.updated', __('messages.log_member_updated', ['member' => $member->name]), $member);

        return back()->with('status', __('messages.member_updated'));
    }

    public function destroy(User $member): RedirectResponse
    {
        $this->authorizeMember($member);
        $name = $member->name;
        $member->delete();

        record_gym_activity(currentGymId(), 'member.deleted', __('messages.log_member_deleted', ['member' => $name]));

        return back()->with('status', __('messages.member_deleted'));
    }

    private function authorizeMember(User $member): void
    {
        abort_unless($member->gym_id === currentGymId() && $member->role === 'member', 403);
    }
}
