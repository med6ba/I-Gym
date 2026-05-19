@php
    $user = auth()->user();
    $items = collect(igym_navigation_items($user))->reject(fn ($item) => $item['route'] === 'settings.index')->take(4);
    $showProfile = ! $user->isSuperAdmin() && ! $user->isReception();
    $navCount = $items->count() + ($showProfile ? 1 : 0) + 1;
    $gridClass = match ($navCount) {
        2 => 'grid-cols-2',
        3 => 'grid-cols-3',
        4 => 'grid-cols-4',
        default => 'grid-cols-5',
    };
@endphp

<nav class="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-white/95 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/95 lg:hidden">
    <div class="grid {{ $gridClass }} gap-1 px-2 py-2">
        @foreach($items as $item)
            @php($active = request()->routeIs($item['active']))
            <a href="{{ route($item['route']) }}" class="{{ $active ? 'bg-amber-100 text-amber-800 shadow-sm dark:bg-amber-950/60 dark:text-amber-200' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }} rounded-xl px-2 py-2 text-center text-xs font-bold transition">
                <x-icon name="{{ $item['icon'] }}" size="19" class="mx-auto mb-1 {{ $active ? 'text-amber-600 dark:text-amber-300' : '' }}" />
                <span class="block truncate">{{ $item['label'] }}</span>
            </a>
        @endforeach
        @if($showProfile)
            @php($profileActive = request()->routeIs('profile.*'))
            <a href="{{ route('profile.edit') }}" class="{{ $profileActive ? 'bg-amber-100 text-amber-800 shadow-sm dark:bg-amber-950/60 dark:text-amber-200' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }} rounded-xl px-2 py-2 text-center text-xs font-bold transition">
                <x-icon name="user" size="19" class="mx-auto mb-1 {{ $profileActive ? 'text-amber-600 dark:text-amber-300' : '' }}" />
                <span class="block truncate">{{ __('messages.profile') }}</span>
            </a>
        @endunless
        <button type="button" x-data x-on:click="$dispatch('open-modal', 'confirm-logout')" class="rounded-xl px-2 py-2 text-center text-xs font-bold text-slate-500 transition hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400">
            <x-icon name="logout" size="19" class="mx-auto mb-1" />
            <span class="block truncate">{{ __('messages.logout') }}</span>
        </button>
    </div>
</nav>
