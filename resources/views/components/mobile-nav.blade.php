@php
    $user = auth()->user();
    $items = collect(igym_navigation_items($user))->reject(fn ($item) => $item['route'] === 'settings.index')->take(4);
@endphp

<nav class="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-white/95 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/95 lg:hidden">
    <div class="grid grid-cols-4 gap-1 px-2 py-2.5">
        @foreach($items as $item)
            @php($active = request()->routeIs($item['active']))
            <a href="{{ route($item['route']) }}" class="{{ $active ? 'bg-amber-100 text-amber-800 shadow-sm dark:bg-amber-950/60 dark:text-amber-200' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }} rounded-xl px-2 py-2.5 text-center text-xs font-bold transition">
                <x-icon name="{{ $item['icon'] }}" size="19" class="mx-auto mb-1 {{ $active ? 'text-amber-600 dark:text-amber-300' : '' }}" />
                <span class="block truncate">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
