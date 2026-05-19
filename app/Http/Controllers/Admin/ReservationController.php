<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.reservations', [
            'reservations' => Reservation::where('gym_id', currentGymId())
                ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
                ->with(['member', 'course.coach'])
                ->latest()
                ->paginate(14)
                ->withQueryString(),
        ]);
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        abort_unless($reservation->gym_id === currentGymId(), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['reserved', 'cancelled', 'attended', 'no_show'])],
        ]);

        $reservation->update($validated);

        record_gym_activity(currentGymId(), 'reservation.updated', __('messages.log_reservation_updated', [
            'member' => $reservation->member?->name ?? __('messages.member'),
            'status' => $validated['status'],
        ]), $reservation);

        return back()->with('status', __('messages.reservation_updated'));
    }
}
