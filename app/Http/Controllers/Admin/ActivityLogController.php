<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymActivityLog;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $logs = GymActivityLog::where('gym_id', currentGymId())
            ->with('actor')
            ->latest()
            ->paginate(18);

        return view('admin.logs', [
            'logs' => $logs,
        ]);
    }
}
