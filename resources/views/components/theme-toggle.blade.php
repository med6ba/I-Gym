@php
    $theme = auth()->user()->theme ?? 'light';
    $themes = [
        'light' => ['label' => __('messages.light'), 'icon' => 'sun'],
        'dark' => ['label' => __('messages.dark'), 'icon' => 'moon'],
        'system' => ['label' => __('messages.system'), 'icon' => 'monitor'],
    ];
@endphp

<div class="relative" x-data="{ open: false, theme: localStorage.getItem('igym-theme') || @js($theme) }" x-on:igym-theme-changed.window="theme = $event.detail">
    <button type="button" x-on:click="open = ! open" class="igym-focus grid size-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30" title="{{ __('messages.theme') }}">
        <span class="sr-only">{{ __('messages.theme') }}</span>
        <x-icon name="palette" size="18" />
    </button>

    <div x-cloak x-show="open" x-transition x-on:click.outside="open = false" class="absolute end-0 z-50 mt-2 w-44 rounded-xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900">
        @foreach($themes as $code => $option)
            <button
                type="button"
                x-on:click="window.igymSetTheme('{{ $code }}'); open = false"
                x-bind:class="theme === '{{ $code }}' ? 'bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-200' : 'text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800'"
                class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-start text-sm font-bold transition"
            >
                <span class="flex items-center gap-3">
                <x-icon name="{{ $option['icon'] }}" size="16" class="text-amber-500" />
                {{ $option['label'] }}
                </span>
                <x-icon name="check" size="15" x-show="theme === '{{ $code }}'" x-cloak />
            </button>
        @endforeach
    </div>
</div>
