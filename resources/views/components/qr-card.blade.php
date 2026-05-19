@props(['payload', 'qrCode'])

<div class="mx-auto max-w-sm rounded-xl border border-slate-200 bg-white p-5 text-center dark:border-slate-800 dark:bg-slate-900">
    <div class="mx-auto inline-block rounded-xl bg-white p-4 text-slate-950">
        {!! $qrCode !!}
    </div>
    <p class="mt-4 break-all rounded-lg bg-slate-100 px-3 py-2 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $payload }}</p>
</div>
