<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = GymActivityLog::where('gym_id', currentGymId())
            ->when($request->filled('action'), fn ($query) => $query->where('action', (string) $request->string('action')))
            ->with('actor')
            ->latest()
            ->paginate(18)
            ->withQueryString();

        return view('admin.logs', [
            'logs' => $logs,
            'actions' => GymActivityLog::where('gym_id', currentGymId())
                ->select('action')
                ->distinct()
                ->orderBy('action')
                ->pluck('action'),
        ]);
    }
}
