@props(['name', 'show' => false, 'maxWidth' => 'lg'])

@php
    $maxWidthClass = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ][$maxWidth] ?? 'max-w-lg';
@endphp

<div
    x-data="{ open: @js($show) }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if (! $event.detail || $event.detail === '{{ $name }}') open = false"
    x-on:close.window="open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-50 grid place-items-center bg-slate-950/70 p-4 backdrop-blur-sm"
    style="display: none;"
>
    <div x-show="open" x-transition.scale.origin.center x-on:click.outside="open = false" {{ $attributes->merge(['class' => 'max-h-[calc(100vh-2rem)] w-full overflow-y-auto '.$maxWidthClass.' rounded-xl border border-slate-200 bg-white p-5 shadow-2xl shadow-slate-950/20 dark:border-slate-800 dark:bg-slate-900']) }}>
        {{ $slot }}
    </div>
</div>
