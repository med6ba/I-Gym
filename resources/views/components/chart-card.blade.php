@props(['title'])

<div {{ $attributes->merge(['class' => 'igym-card p-5']) }}>
    <div class="mb-4 flex items-center justify-between gap-4">
        <h3 class="text-base font-bold text-slate-950 dark:text-white">{{ $title }}</h3>
        {{ $action ?? '' }}
    </div>
    {{ $slot }}
</div>
