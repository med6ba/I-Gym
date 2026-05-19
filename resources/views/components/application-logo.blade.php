@props(['tone' => 'default'])

@php
    $titleClass = $tone === 'inverse' ? 'text-white' : 'text-slate-950 dark:text-white';
    $subtitleClass = $tone === 'inverse' ? 'text-amber-200' : 'text-amber-600 dark:text-amber-300';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <img
        src="{{ asset('icons/igym-logo.svg') }}"
        alt="I-Gym"
        class="size-11 shrink-0 rounded-2xl object-cover"
    >
    <div>
        <div class="text-xl font-black tracking-normal {{ $titleClass }}">I-Gym</div>
        <div class="text-xs font-bold uppercase tracking-normal {{ $subtitleClass }}">{{ __('messages.fitness_app') }}</div>
    </div>
</div>
