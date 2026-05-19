@props(['label', 'value', 'detail' => null, 'trend' => null])

<div {{ $attributes->merge(['class' => 'igym-card igym-hover p-5']) }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="mt-2 text-3xl font-black tracking-normal text-slate-950 dark:text-white">{{ $value }}</p>
        </div>
        @if($trend)
            <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-bold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">{{ $trend }}</span>
        @endif
    </div>
    @if($detail)
        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ $detail }}</p>
    @endif
</div>
