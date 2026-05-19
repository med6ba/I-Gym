@props(['status' => null])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold shadow-sm shadow-slate-950/5 '.status_badge_class($status)]) }}>
    <span class="size-1.5 rounded-full bg-current opacity-70"></span>
    {{ $slot->isEmpty() ? Str::headline((string) $status) : $slot }}
</span>
