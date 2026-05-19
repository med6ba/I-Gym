<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        return view('member.reservations', [
            'reservations' => Reservation::where('gym_id', auth()->user()->gym_id)
                ->where('user_id', auth()->id())
                ->with('course.coach')
                ->latest()
                ->paginate(12),
        ]);
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        abort_unless($reservation->gym_id === auth()->user()->gym_id && $reservation->user_id === auth()->id(), 403);

        if ($reservation->status === 'reserved') {
            $reservation->update(['status' => 'cancelled']);
        }

        return back()->with('status', __('messages.reservation_cancelled'));
    }
}
