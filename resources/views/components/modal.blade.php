@props(['name'])

<div x-data="{ open: false }" x-on:open-modal.window="open = $event.detail === '{{ $name }}'" x-show="open" class="fixed inset-0 z-50 grid place-items-center bg-slate-950/60 p-4" style="display: none;">
    <div x-on:click.outside="open = false" class="w-full max-w-lg rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
        {{ $slot }}
    </div>
</div>
