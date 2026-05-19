<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberProgress;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function __invoke(): View
    {
        $progress = MemberProgress::where('member_id', auth()->id())->orderBy('recorded_at')->get();
        $latest = $progress->last();

        return view('member.progress', [
            'progress' => $progress,
            'latest' => $latest,
            'progressChart' => [
                'labels' => $progress->pluck('recorded_at')->map(fn ($date) => $date->format('M d')),
                'weight' => $progress->pluck('weight'),
                'bodyFat' => $progress->pluck('body_fat'),
            ],
        ]);
    }
}
