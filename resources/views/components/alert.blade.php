@props(['type' => 'info'])

<div {{ $attributes->merge(['class' => 'rounded-xl border px-4 py-3 text-sm '.status_badge_class($type)]) }}>
    {{ $slot }}
</div>
