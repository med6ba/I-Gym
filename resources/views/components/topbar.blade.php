@php
    $user = auth()->user();
    $quickActions = match ($user->role) {
        'super_admin' => [
            ['label' => __('messages.new_gym_customer'), 'route' => 'super.gyms.create', 'icon' => 'plus'],
            ['label' => __('messages.analytics'), 'route' => 'super.analytics', 'icon' => 'chart'],
        ],
        'gym_admin' => [
            ['label' => __('messages.create_course'), 'route' => 'admin.courses.index', 'icon' => 'calendar'],
            ['label' => __('messages.scan_qr_access'), 'route' => 'admin.attendance.index', 'icon' => 'qr'],
            ['label' => __('messages.notifications'), 'route' => 'admin.notifications.index', 'icon' => 'bell'],
        ],
        'coach' => [
            ['label' => __('messages.classes'), 'route' => 'coach.classes.index', 'icon' => 'calendar'],
            ['label' => __('messages.training_plans'), 'route' => 'coach.training-plans.index', 'icon' => 'target'],
            ['label' => __('messages.progress'), 'route' => 'coach.progress.index', 'icon' => 'activity'],
        ],
        default => [
            ['label' => __('messages.book_class'), 'route' => 'member.courses.index', 'icon' => 'calendar'],
            ['label' => __('messages.qr_code'), 'route' => 'member.qr-code', 'icon' => 'qr'],
            ['label' => __('messages.subscription'), 'route' => 'member.subscription', 'icon' => 'credit-card'],
        ],
    };
@endphp

<div class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95" x-data="{ quick: false, userMenu: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <div class="min-w-0">
            <p class="truncate text-sm font-bold text-amber-600 dark:text-amber-300">{{ $user->gym?->name ?? __('messages.global_saas') }}</p>
            <h1 class="truncate text-lg font-black text-slate-950 dark:text-white">{{ __('messages.smart_fitness_management') }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <div class="relative hidden md:block">
                <button type="button" x-on:click="quick = ! quick; userMenu = false" class="igym-focus inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30">
                    <x-icon name="sparkles" size="17" class="text-amber-500" />
                    {{ __('messages.quick') }}
                    <x-icon name="chevron-down" size="15" />
                </button>
                <div x-show="quick" x-cloak x-transition x-on:click.outside="quick = false" class="absolute end-0 mt-2 w-64 rounded-xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900">
                    @foreach($quickActions as $action)
                        <a href="{{ route($action['route']) }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-amber-50 hover:text-amber-800 dark:text-slate-200 dark:hover:bg-amber-950/30 dark:hover:text-amber-200">
                            <x-icon name="{{ $action['icon'] }}" size="18" class="text-amber-500" />
                            {{ $action['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden items-center gap-2 sm:flex">
                <x-language-switcher />
                <x-theme-toggle />
            </div>

            <div class="relative">
                <button type="button" x-on:click="userMenu = ! userMenu; quick = false" class="igym-focus inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-2 py-2 text-sm font-bold text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30 sm:px-3">
                    <span class="grid size-8 place-items-center rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="size-8 rounded-lg object-cover">
                    </span>
                    <span class="hidden max-w-28 truncate sm:inline">{{ $user->name }}</span>
                    <x-icon name="chevron-down" size="15" class="hidden sm:block" />
                </button>

                <div x-show="userMenu" x-cloak x-transition x-on:click.outside="userMenu = false" class="absolute end-0 mt-2 w-72 rounded-xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-100 px-3 py-3 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="size-11 rounded-xl object-cover">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-black text-slate-950 dark:text-white">{{ $user->name }}</p>
                                <p class="truncate text-xs font-medium text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="mt-2 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-amber-50 hover:text-amber-800 dark:text-slate-200 dark:hover:bg-amber-950/30 dark:hover:text-amber-200">
                        <x-icon name="user" size="18" class="text-amber-500" />
                        {{ __('messages.profile') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-slate-100 pt-2 dark:border-slate-800">
                        @csrf
                        <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-start text-sm font-bold text-rose-600 transition hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-950/30">
                            <x-icon name="logout" size="18" />
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
