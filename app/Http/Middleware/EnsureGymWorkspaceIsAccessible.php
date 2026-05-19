<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureGymWorkspaceIsAccessible
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isSuperAdmin()) {
            return $next($request);
        }

        if (! $user->gym_id || ! $user->gym) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'This account is not attached to an active gym workspace.',
            ]);
        }

        if (! in_array($user->gym->status, ['active', 'trial'], true)) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'This gym workspace is not active. Please contact the platform owner.',
            ]);
        }

        return $next($request);
    }
}
