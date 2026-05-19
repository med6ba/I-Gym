<div class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <div class="min-w-0">
            <p class="truncate text-sm font-medium text-slate-500 dark:text-slate-400">{{ auth()->user()->gym?->name ?? __('messages.global_saas') }}</p>
            <h1 class="truncate text-lg font-black text-slate-950 dark:text-white">{{ __('messages.smart_fitness_management') }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <x-language-switcher />
            <x-theme-toggle />
            <a href="{{ route('profile.edit') }}" class="igym-focus hidden rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30 sm:inline-flex">
                {{ __('messages.profile') }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                @csrf
                <button class="igym-focus rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30">
                    {{ __('messages.logout') }}
                </button>
            </form>
        </div>
    </div>
</div>
