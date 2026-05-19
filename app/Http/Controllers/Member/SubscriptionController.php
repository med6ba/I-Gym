<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __invoke(): View
    {
        return view('member.subscription', [
            'subscriptions' => Subscription::where('user_id', auth()->id())->latest('ends_at')->get(),
            'current' => Subscription::where('user_id', auth()->id())->latest('ends_at')->first(),
        ]);
    }
}
