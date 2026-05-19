<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function __invoke(): View
    {
        $member = auth()->user();
        $payload = 'IGYM|member:'.$member->id.'|gym:'.$member->gym_id.'|issued:'.now()->timestamp;

        return view('member.qr-code', [
            'payload' => $payload,
            'qrCode' => QrCode::size(260)->margin(2)->generate($payload),
        ]);
    }
}
