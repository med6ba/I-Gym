<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /** @var array<int, string> */
    private array $supported = ['en', 'fr', 'es', 'ar'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->user()?->language
            ?? $request->session()->get('locale')
            ?? $request->cookie('igym_locale')
            ?? config('app.locale', 'en');

        if (! in_array($locale, $this->supported, true)) {
            $locale = 'en';
        }

        App::setLocale($locale);
        $request->session()->put('locale', $locale);

        $response = $next($request);

        return $response->withCookie(cookie()->forever('igym_locale', $locale));
    }
}
