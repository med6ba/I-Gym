@php
    $user = auth()->user();
    $items = igym_navigation_items($user);
@endphp

<aside x-data="{ isRtl: document.documentElement.dir === 'rtl' }"
       class="fixed inset-y-0 start-0 z-50 w-72 border-e border-slate-200 bg-white px-4 py-5 dark:border-slate-800 dark:bg-slate-900 flex flex-col transition-transform duration-300 ease-in-out"
       x-bind:style="sidebarOpen || windowWidth >= 1024 ? 'transform: translateX(0)' : 'transform: ' + (isRtl ? 'translateX(100%)' : 'translateX(-100%)')">
    <div class="flex items-center justify-between shrink-0">
        <a href="{{ role_home_route() }}" class="igym-focus rounded-xl">
            <x-application-logo />
        </a>
        <button type="button" x-on:click="sidebarOpen = false; document.body.classList.remove('overflow-hidden')" class="igym-focus grid size-10 place-items-center rounded-xl text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 lg:hidden">
            <x-icon name="x" size="18" />
        </button>
    </div>

    <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/60 dark:bg-amber-950/30 shrink-0">
        <div class="flex items-center gap-3">
            <span class="grid size-9 place-items-center rounded-lg bg-white text-amber-700 dark:bg-slate-900 dark:text-amber-300">
                <x-icon name="{{ $user->isSuperAdmin() ? 'shield' : 'building' }}" size="18" />
            </span>
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase text-amber-700 dark:text-amber-300">{{ __('messages.'.$user->role) }}</p>
                <p class="mt-1 truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $user->gym?->name ?? __('messages.global_saas') }}</p>
            </div>
        </div>
    </div>

    <nav class="mt-6 flex-1 space-y-1 overflow-y-auto">
        @foreach($items as $item)
            @php($active = request()->routeIs($item['active']))
            <a href="{{ route($item['route']) }}" class="{{ $active ? 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-200' : 'border-transparent text-slate-600 hover:border-slate-200 hover:bg-slate-50 dark:text-slate-300 dark:hover:border-slate-800 dark:hover:bg-slate-800/60' }} group flex items-center justify-between rounded-xl border px-3 py-2.5 text-sm font-bold transition">
                <span class="flex items-center gap-3">
                    <x-icon name="{{ $item['icon'] }}" size="18" class="{{ $active ? 'text-amber-600 dark:text-amber-300' : 'text-slate-400 group-hover:text-amber-500' }}" />
                    <span>{{ $item['label'] }}</span>
                </span>
                <x-icon name="arrow-right" size="16" class="opacity-0 transition group-hover:translate-x-0.5 group-hover:opacity-100 rtl:rotate-180" />
            </a>
        @endforeach
    </nav>

    <div class="mt-auto space-y-3 pt-6">
        @unless($user->isSuperAdmin() || $user->isReception())
            <a href="{{ route('profile.edit') }}" class="igym-focus group flex items-center gap-3 rounded-xl border border-slate-200 p-3 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-800 dark:hover:border-amber-800 dark:hover:bg-amber-950/30">
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="size-11 rounded-xl object-cover">
                <span class="min-w-0">
                    <span class="block truncate text-sm font-black text-slate-950 dark:text-white">{{ $user->name }}</span>
                    <span class="mt-0.5 block truncate text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('messages.profile') }}</span>
                </span>
            </a>
        @else
            <div class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 dark:border-slate-800">
                <span class="grid size-11 place-items-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    <x-icon name="shield" size="19" />
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-sm font-black text-slate-950 dark:text-white">{{ $user->name }}</span>
                    <span class="mt-0.5 block truncate text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $user->isSuperAdmin() ? __('messages.global_saas') : $user->gym?->name ?? __('messages.reception') }}</span>
                </span>
            </div>
        @endunless

        <button type="button" x-data x-on:click="$dispatch('open-modal', 'confirm-logout')" class="igym-focus group flex w-full items-center gap-3 rounded-xl px-3 py-2 text-start text-sm font-bold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="logout" size="18" class="text-slate-400 group-hover:text-rose-500" />
            {{ __('messages.logout') }}
        </button>
    </div>
</aside>

<x-modal name="confirm-logout">
    <form method="POST" action="{{ route('logout') }}" class="space-y-5" x-on:submit="localStorage.removeItem('igyma-messages')">
        @csrf
        <div>
            <p class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.logout_confirm_title') }}</p>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ __('messages.logout_confirm_body') }}</p>
        </div>
        <div class="flex justify-end gap-3">
            <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'confirm-logout')">{{ __('messages.cancel') }}</x-button>
            <x-button variant="danger">{{ __('messages.logout') }}</x-button>
        </div>
    </form>
</x-modal>
