@props(['title' => null, 'message' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700']) }}>
    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $title ?? __('messages.no_data') }}</h3>
    @if($message)
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $message }}</p>
    @endif
    <div class="mt-4">{{ $slot }}</div>
</div>
