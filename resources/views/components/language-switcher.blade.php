@php
    $current = app()->getLocale();
    $languages = [
        'en' => ['label' => 'English', 'short' => 'EN'],
        'fr' => ['label' => 'Français', 'short' => 'FR'],
        'es' => ['label' => 'Español', 'short' => 'ES'],
        'ar' => ['label' => 'العربية', 'short' => 'AR'],
    ];
@endphp

<div class="relative" x-data="{ open: false }">
    <button type="button" x-on:click="open = ! open" class="igym-focus grid size-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30" title="{{ __('messages.language') }}">
        <span class="sr-only">{{ __('messages.language') }}</span>
        <x-icon name="globe" size="18" />
    </button>

    <div x-cloak x-show="open" x-transition x-on:click.outside="open = false" class="absolute end-0 z-50 mt-2 w-48 rounded-xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900">
        @foreach($languages as $code => $language)
            <form method="POST" action="{{ route('settings.language.update') }}">
                @csrf
                <input type="hidden" name="language" value="{{ $code }}">
                <button class="{{ $current === $code ? 'bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-200' : 'text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800' }} flex w-full items-center justify-between rounded-lg px-3 py-2 text-start text-sm font-bold transition">
                    <span>{{ $language['label'] }}</span>
                    <span class="text-xs font-black">{{ $language['short'] }}</span>
                </button>
            </form>
        @endforeach
    </div>
</div>
