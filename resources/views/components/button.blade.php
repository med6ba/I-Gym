@props(['variant' => 'primary'])

@php
    $classes = match ($variant) {
        'secondary' => 'border border-slate-200 bg-white text-slate-700 hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30',
        'danger' => 'border border-rose-200 bg-rose-600 text-white hover:bg-rose-700 dark:border-rose-800',
        default => 'border border-amber-500 bg-amber-500 text-slate-950 hover:bg-amber-400 hover:border-amber-400',
    };
@endphp

<button {{ $attributes->merge(['class' => 'igym-focus inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-bold transition active:scale-[0.99] '.$classes]) }}>
    {{ $slot }}
</button>
