<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        return view('admin.subscriptions', [
            'subscriptions' => Subscription::where('gym_id', currentGymId())->with('member')->latest('ends_at')->paginate(14),
            'members' => User::where('gym_id', currentGymId())->role('member')->orderBy('name')->get(),
            'expiring' => Subscription::where('gym_id', currentGymId())->expiringSoon()->with('member')->get(),
        ]);
    }

    public function store(SubscriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeMember($data['user_id']);
        $subscription = Subscription::create($data + ['gym_id' => currentGymId()]);

        record_gym_activity(currentGymId(), 'subscription.created', __('messages.log_subscription_created', [
            'member' => $subscription->member?->name ?? __('messages.member'),
        ]), $subscription);

        return back()->with('status', __('messages.subscription_created'));
    }

    public function update(SubscriptionRequest $request, Subscription $subscription): RedirectResponse
    {
        abort_unless($subscription->gym_id === currentGymId(), 403);
        $data = $request->validated();
        $this->authorizeMember($data['user_id']);
        $subscription->update($data);

        record_gym_activity(currentGymId(), 'subscription.updated', __('messages.log_subscription_updated', [
            'member' => $subscription->member?->name ?? __('messages.member'),
        ]), $subscription);

        return back()->with('status', __('messages.subscription_updated'));
    }

    private function authorizeMember(int $memberId): void
    {
        abort_unless(User::where('gym_id', currentGymId())->role('member')->whereKey($memberId)->exists(), 403);
    }
}
