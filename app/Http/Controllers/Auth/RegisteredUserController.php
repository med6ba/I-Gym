<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('login')->with('status', __('messages.registration_invite_only'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(): RedirectResponse
    {
        return redirect()->route('login')->withErrors([
            'email' => __('messages.registration_invite_only'),
        ]);
    }
}
