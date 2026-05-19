@php
    $user = auth()->user();
    $items = match ($user->role) {
        'super_admin' => [
            ['label' => __('messages.dashboard'), 'route' => 'super.dashboard', 'active' => 'super.dashboard'],
            ['label' => __('messages.gyms'), 'route' => 'super.gyms.index', 'active' => 'super.gyms.*'],
            ['label' => __('messages.analytics'), 'route' => 'super.analytics', 'active' => 'super.analytics'],
        ],
        'gym_admin' => [
            ['label' => __('messages.dashboard'), 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
            ['label' => __('messages.members'), 'route' => 'admin.members.index', 'active' => 'admin.members.*'],
            ['label' => __('messages.coaches'), 'route' => 'admin.coaches.index', 'active' => 'admin.coaches.*'],
            ['label' => __('messages.courses'), 'route' => 'admin.courses.index', 'active' => 'admin.courses.*'],
            ['label' => __('messages.reservations'), 'route' => 'admin.reservations.index', 'active' => 'admin.reservations.*'],
            ['label' => __('messages.subscriptions'), 'route' => 'admin.subscriptions.index', 'active' => 'admin.subscriptions.*'],
            ['label' => __('messages.attendance'), 'route' => 'admin.attendance.index', 'active' => 'admin.attendance.*'],
            ['label' => __('messages.notifications'), 'route' => 'admin.notifications.index', 'active' => 'admin.notifications.*'],
        ],
        'coach' => [
            ['label' => __('messages.dashboard'), 'route' => 'coach.dashboard', 'active' => 'coach.dashboard'],
            ['label' => __('messages.classes'), 'route' => 'coach.classes.index', 'active' => 'coach.classes.*'],
            ['label' => __('messages.members'), 'route' => 'coach.members.index', 'active' => 'coach.members.*'],
            ['label' => __('messages.training_plans'), 'route' => 'coach.training-plans.index', 'active' => 'coach.training-plans.*'],
            ['label' => __('messages.progress'), 'route' => 'coach.progress.index', 'active' => 'coach.progress.*'],
        ],
        default => [
            ['label' => __('messages.dashboard'), 'route' => 'member.dashboard', 'active' => 'member.dashboard'],
            ['label' => __('messages.qr_code'), 'route' => 'member.qr-code', 'active' => 'member.qr-code'],
            ['label' => __('messages.courses'), 'route' => 'member.courses.index', 'active' => 'member.courses.*'],
            ['label' => __('messages.reservations'), 'route' => 'member.reservations.index', 'active' => 'member.reservations.*'],
            ['label' => __('messages.subscription'), 'route' => 'member.subscription', 'active' => 'member.subscription'],
            ['label' => __('messages.progress'), 'route' => 'member.progress', 'active' => 'member.progress'],
            ['label' => __('messages.notifications'), 'route' => 'member.notifications.index', 'active' => 'member.notifications.*'],
        ],
    };
@endphp

<aside class="hidden w-72 shrink-0 border-e border-slate-200 bg-white px-4 py-5 dark:border-slate-800 dark:bg-slate-900 lg:flex lg:flex-col">
    <a href="{{ role_home_route() }}" class="igym-focus rounded-lg">
        <x-application-logo />
    </a>

    <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/60 dark:bg-amber-950/30">
        <p class="text-xs font-bold uppercase text-amber-700 dark:text-amber-300">{{ Str::headline($user->role) }}</p>
        <p class="mt-1 truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $user->gym?->name ?? __('messages.global_saas') }}</p>
    </div>

    <nav class="mt-6 space-y-1">
        @foreach($items as $item)
            @php($active = request()->routeIs($item['active']))
            <a href="{{ route($item['route']) }}" class="{{ $active ? 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-200' : 'border-transparent text-slate-600 hover:border-slate-200 hover:bg-slate-50 dark:text-slate-300 dark:hover:border-slate-800 dark:hover:bg-slate-800/60' }} flex items-center justify-between rounded-lg border px-3 py-2.5 text-sm font-semibold transition">
                <span>{{ $item['label'] }}</span>
                <span class="transition {{ $active ? 'translate-x-0' : 'rtl:rotate-180' }}">›</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto space-y-2 pt-6">
        <a href="{{ route('settings.language') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">{{ __('messages.settings') }}</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="igym-focus w-full rounded-lg px-3 py-2 text-start text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">
                {{ __('messages.logout') }}
            </button>
        </form>
    </div>
</aside>
