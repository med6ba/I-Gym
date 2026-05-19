@php
    $user = auth()->user();
    $items = match ($user->role) {
        'super_admin' => [
            ['label' => __('messages.dashboard'), 'route' => 'super.dashboard', 'active' => 'super.dashboard', 'icon' => 'dashboard'],
            ['label' => __('messages.gyms'), 'route' => 'super.gyms.index', 'active' => 'super.gyms.*', 'icon' => 'building'],
            ['label' => __('messages.analytics'), 'route' => 'super.analytics', 'active' => 'super.analytics', 'icon' => 'chart'],
        ],
        'gym_admin' => [
            ['label' => __('messages.dashboard'), 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'dashboard'],
            ['label' => __('messages.members'), 'route' => 'admin.members.index', 'active' => 'admin.members.*', 'icon' => 'users'],
            ['label' => __('messages.coaches'), 'route' => 'admin.coaches.index', 'active' => 'admin.coaches.*', 'icon' => 'coach'],
            ['label' => __('messages.courses'), 'route' => 'admin.courses.index', 'active' => 'admin.courses.*', 'icon' => 'calendar'],
            ['label' => __('messages.reservations'), 'route' => 'admin.reservations.index', 'active' => 'admin.reservations.*', 'icon' => 'attendance'],
            ['label' => __('messages.subscriptions'), 'route' => 'admin.subscriptions.index', 'active' => 'admin.subscriptions.*', 'icon' => 'credit-card'],
            ['label' => __('messages.attendance'), 'route' => 'admin.attendance.index', 'active' => 'admin.attendance.*', 'icon' => 'qr'],
            ['label' => __('messages.notifications'), 'route' => 'admin.notifications.index', 'active' => 'admin.notifications.*', 'icon' => 'bell'],
        ],
        'coach' => [
            ['label' => __('messages.dashboard'), 'route' => 'coach.dashboard', 'active' => 'coach.dashboard', 'icon' => 'dashboard'],
            ['label' => __('messages.classes'), 'route' => 'coach.classes.index', 'active' => 'coach.classes.*', 'icon' => 'calendar'],
            ['label' => __('messages.members'), 'route' => 'coach.members.index', 'active' => 'coach.members.*', 'icon' => 'users'],
            ['label' => __('messages.training_plans'), 'route' => 'coach.training-plans.index', 'active' => 'coach.training-plans.*', 'icon' => 'target'],
            ['label' => __('messages.progress'), 'route' => 'coach.progress.index', 'active' => 'coach.progress.*', 'icon' => 'activity'],
        ],
        default => [
            ['label' => __('messages.dashboard'), 'route' => 'member.dashboard', 'active' => 'member.dashboard', 'icon' => 'dashboard'],
            ['label' => __('messages.qr_code'), 'route' => 'member.qr-code', 'active' => 'member.qr-code', 'icon' => 'qr'],
            ['label' => __('messages.courses'), 'route' => 'member.courses.index', 'active' => 'member.courses.*', 'icon' => 'calendar'],
            ['label' => __('messages.reservations'), 'route' => 'member.reservations.index', 'active' => 'member.reservations.*', 'icon' => 'attendance'],
            ['label' => __('messages.subscription'), 'route' => 'member.subscription', 'active' => 'member.subscription', 'icon' => 'credit-card'],
            ['label' => __('messages.progress'), 'route' => 'member.progress', 'active' => 'member.progress', 'icon' => 'activity'],
            ['label' => __('messages.notifications'), 'route' => 'member.notifications.index', 'active' => 'member.notifications.*', 'icon' => 'bell'],
        ],
    };
@endphp

<aside class="hidden w-72 shrink-0 border-e border-slate-200 bg-white px-4 py-5 dark:border-slate-800 dark:bg-slate-900 lg:flex lg:flex-col">
    <a href="{{ role_home_route() }}" class="igym-focus rounded-xl">
        <x-application-logo />
    </a>

    <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/60 dark:bg-amber-950/30">
        <div class="flex items-center gap-3">
            <span class="grid size-9 place-items-center rounded-lg bg-white text-amber-700 dark:bg-slate-900 dark:text-amber-300">
                <x-icon name="{{ $user->isSuperAdmin() ? 'shield' : 'building' }}" size="18" />
            </span>
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase text-amber-700 dark:text-amber-300">{{ Str::headline($user->role) }}</p>
                <p class="mt-1 truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $user->gym?->name ?? __('messages.global_saas') }}</p>
            </div>
        </div>
    </div>

    <nav class="mt-6 space-y-1">
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

    <div class="mt-auto space-y-2 pt-6">
        <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="user" size="18" class="text-slate-400 group-hover:text-amber-500" />
            {{ __('messages.profile') }}
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="igym-focus group flex w-full items-center gap-3 rounded-xl px-3 py-2 text-start text-sm font-bold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">
                <x-icon name="logout" size="18" class="text-slate-400 group-hover:text-rose-500" />
                {{ __('messages.logout') }}
            </button>
        </form>
    </div>
</aside>
