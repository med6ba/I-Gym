<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressRequest;
use App\Models\MemberProgress;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function index(): View
    {
        return view('coach.progress', [
            'progressEntries' => MemberProgress::where('gym_id', auth()->user()->gym_id)->with('member')->latest('recorded_at')->paginate(14),
            'members' => User::where('gym_id', auth()->user()->gym_id)->role('member')->orderBy('name')->get(),
        ]);
    }

    public function store(ProgressRequest $request): RedirectResponse
    {
        $data = $request->validated();
        abort_unless(User::where('gym_id', auth()->user()->gym_id)->role('member')->whereKey($data['member_id'])->exists(), 403);

        MemberProgress::create($data + ['gym_id' => auth()->user()->gym_id]);

        return back()->with('status', __('messages.progress_recorded'));
    }
}
