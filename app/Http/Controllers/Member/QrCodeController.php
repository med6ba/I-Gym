<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function __invoke(): View
    {
        $member = auth()->user();
        $hasActiveSubscription = Subscription::where('user_id', $member->id)
            ->where('status', 'active')
            ->whereDate('ends_at', '>=', today())
            ->exists();

        return view('member.qr-code', [
            'hasActiveSubscription' => $hasActiveSubscription,
        ]);
    }

    public function code(): JsonResponse
    {
        $member = auth()->user();
        $hasActiveSubscription = Subscription::where('user_id', $member->id)
            ->where('status', 'active')
            ->whereDate('ends_at', '>=', today())
            ->exists();

        abort_unless($hasActiveSubscription, 403);

        $payload = 'IGYM|member:'.$member->id.'|gym:'.$member->gym_id.'|issued:'.now()->timestamp;

        return response()->json([
            'payload' => $payload,
            'qrCode' => (string) QrCode::size(260)->margin(2)->generate($payload),
        ]);
    }
}
