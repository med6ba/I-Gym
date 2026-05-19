@props(['value' => 0])

<div class="h-2.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
    <div class="h-full rounded-full bg-amber-500 transition-all" style="width: {{ min(100, max(0, (int) $value)) }}%"></div>
</div>
