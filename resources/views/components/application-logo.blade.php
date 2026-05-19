@props(['tone' => 'default'])

@php
    $titleClass = $tone === 'inverse' ? 'text-white' : 'text-slate-950 dark:text-white';
    $subtitleClass = $tone === 'inverse' ? 'text-amber-200' : 'text-amber-600 dark:text-amber-300';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <div class="relative grid size-11 place-items-center rounded-2xl border border-amber-300 bg-amber-500 text-slate-950">
        <x-icon name="dumbbell" size="22" />
        <span class="absolute -end-1 -top-1 size-3 rounded-full border-2 border-white bg-emerald-400 dark:border-slate-950"></span>
    </div>
    <div>
        <div class="text-xl font-black tracking-normal {{ $titleClass }}">I-Gym</div>
        <div class="text-xs font-bold uppercase tracking-normal {{ $subtitleClass }}">Fitness SaaS</div>
    </div>
</div>
