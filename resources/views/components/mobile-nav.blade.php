@php
    $user = auth()->user();
    $items = match ($user->role) {
        'super_admin' => [
            ['label' => __('messages.dashboard'), 'route' => 'super.dashboard', 'active' => 'super.dashboard', 'icon' => 'dashboard'],
            ['label' => __('messages.gyms'), 'route' => 'super.gyms.index', 'active' => 'super.gyms.*', 'icon' => 'building'],
            ['label' => __('messages.analytics'), 'route' => 'super.analytics', 'active' => 'super.analytics', 'icon' => 'chart'],
            ['label' => __('messages.profile'), 'route' => 'profile.edit', 'active' => 'profile.*', 'icon' => 'user'],
        ],
        'gym_admin' => [
            ['label' => __('messages.dashboard'), 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'dashboard'],
            ['label' => __('messages.members'), 'route' => 'admin.members.index', 'active' => 'admin.members.*', 'icon' => 'users'],
            ['label' => __('messages.courses'), 'route' => 'admin.courses.index', 'active' => 'admin.courses.*', 'icon' => 'calendar'],
            ['label' => __('messages.attendance'), 'route' => 'admin.attendance.index', 'active' => 'admin.attendance.*', 'icon' => 'qr'],
        ],
        'coach' => [
            ['label' => __('messages.dashboard'), 'route' => 'coach.dashboard', 'active' => 'coach.dashboard', 'icon' => 'dashboard'],
            ['label' => __('messages.classes'), 'route' => 'coach.classes.index', 'active' => 'coach.classes.*', 'icon' => 'calendar'],
            ['label' => __('messages.members'), 'route' => 'coach.members.index', 'active' => 'coach.members.*', 'icon' => 'users'],
            ['label' => __('messages.progress'), 'route' => 'coach.progress.index', 'active' => 'coach.progress.*', 'icon' => 'activity'],
        ],
        default => [
            ['label' => __('messages.dashboard'), 'route' => 'member.dashboard', 'active' => 'member.dashboard', 'icon' => 'dashboard'],
            ['label' => __('messages.qr_code'), 'route' => 'member.qr-code', 'active' => 'member.qr-code', 'icon' => 'qr'],
            ['label' => __('messages.courses'), 'route' => 'member.courses.index', 'active' => 'member.courses.*', 'icon' => 'calendar'],
            ['label' => __('messages.progress'), 'route' => 'member.progress', 'active' => 'member.progress', 'icon' => 'activity'],
        ],
    };
@endphp

<nav class="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-white px-2 py-2 dark:border-slate-800 dark:bg-slate-900 lg:hidden">
    <div class="grid grid-cols-4 gap-1">
        @foreach($items as $item)
            @php($active = request()->routeIs($item['active']))
            <a href="{{ route($item['route']) }}" class="{{ $active ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-200' : 'text-slate-500 dark:text-slate-400' }} rounded-xl px-2 py-2 text-center text-xs font-bold transition">
                <x-icon name="{{ $item['icon'] }}" size="19" class="mx-auto mb-1" />
                <span class="block truncate">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
