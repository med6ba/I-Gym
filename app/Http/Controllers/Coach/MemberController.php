<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $courseIds = Course::assignedTo(auth()->user())->pluck('id');
        $memberIds = Reservation::whereIn('course_id', $courseIds)->pluck('user_id')->unique();

        return view('coach.members', [
            'members' => User::whereIn('id', $memberIds)->with(['activeSubscription', 'progressEntries' => fn ($query) => $query->latest('recorded_at')])->paginate(12),
        ]);
    }
}
