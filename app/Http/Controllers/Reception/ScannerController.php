<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ScannerController extends Controller
{
    public function index(): View
    {
        return view('reception.scanner');
    }
}