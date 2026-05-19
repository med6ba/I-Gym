<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingPlanRequest;
use App\Models\TrainingPlan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrainingPlanController extends Controller
{
    public function index(): View
    {
        return view('coach.training-plans', [
            'plans' => TrainingPlan::where('gym_id', auth()->user()->gym_id)->where('coach_id', auth()->id())->with('member')->latest()->paginate(12),
            'members' => User::where('gym_id', auth()->user()->gym_id)->role('member')->orderBy('name')->get(),
        ]);
    }

    public function store(TrainingPlanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeMember((int) $data['member_id']);
        $data['exercises'] = filled($data['exercises'] ?? null)
            ? collect(preg_split('/\r\n|\r|\n/', $data['exercises']))->filter()->values()->all()
            : null;

        $plan = TrainingPlan::create($data + [
            'gym_id' => auth()->user()->gym_id,
            'coach_id' => auth()->id(),
        ]);

        record_gym_activity(auth()->user()->gym_id, 'training_plan.created', __('messages.log_training_plan_created', ['plan' => $plan->title]), $plan);

        return back()->with('status', __('messages.training_plan_created'));
    }

    private function authorizeMember(int $memberId): void
    {
        abort_unless(User::where('gym_id', auth()->user()->gym_id)->role('member')->whereKey($memberId)->exists(), 403);
    }
}
