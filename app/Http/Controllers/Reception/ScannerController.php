<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class ScannerController extends Controller
{
    public function index(): View
    {
        return view('reception.scanner', [
            'members' => User::where('gym_id', currentGymId())
                ->role('member')
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
        ]);
    }
}
