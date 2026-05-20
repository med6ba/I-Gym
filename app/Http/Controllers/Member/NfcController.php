<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\View\View;

class NfcController extends Controller
{
    public function index(): View
    {
        return view('member.nfc', [
            'hasActiveSubscription' => Subscription::where('user_id', auth()->id())->where('status', 'active')->exists(),
        ]);
    }
}
