<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function language(): View
    {
        return view('settings.language');
    }

    public function theme(): View
    {
        return view('settings.theme');
    }

    public function updateLanguage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language' => ['required', Rule::in(['en', 'fr', 'es', 'ar'])],
        ]);

        App::setLocale($validated['language']);
        $request->session()->put('locale', $validated['language']);

        if ($request->user()) {
            $request->user()->update(['language' => $validated['language']]);
        }

        return back()->with('status', __('messages.settings_saved'));
    }

    public function updateTheme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', Rule::in(['light', 'dark', 'system'])],
        ]);

        if ($request->user()) {
            $request->user()->update(['theme' => $validated['theme']]);
        }

        return back()->with('status', __('messages.settings_saved'));
    }
}
