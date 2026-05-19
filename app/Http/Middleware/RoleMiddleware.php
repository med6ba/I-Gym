<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'This workspace is reserved for another I-Gym role.');
        }

        if ($user->status !== 'active') {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account is inactive. Please contact your gym administrator.',
            ]);
        }

        if (! $user->isSuperAdmin() && $user->gym?->status === 'suspended') {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'This gym workspace is suspended.',
            ]);
        }

        return $next($request);
    }
}
