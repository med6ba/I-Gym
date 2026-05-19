@props(['status' => null])

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold '.status_badge_class($status)]) }}>
    {{ $slot->isEmpty() ? Str::headline((string) $status) : $slot }}
</span>
