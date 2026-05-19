@php
    $user = auth()->user();
    $items = match ($user->role) {
        'super_admin' => [
            ['label' => __('messages.dashboard'), 'route' => 'super.dashboard', 'active' => 'super.dashboard'],
            ['label' => __('messages.gyms'), 'route' => 'super.gyms.index', 'active' => 'super.gyms.*'],
            ['label' => __('messages.analytics'), 'route' => 'super.analytics', 'active' => 'super.analytics'],
            ['label' => __('messages.settings'), 'route' => 'settings.language', 'active' => 'settings.*'],
        ],
        'gym_admin' => [
            ['label' => __('messages.dashboard'), 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
            ['label' => __('messages.members'), 'route' => 'admin.members.index', 'active' => 'admin.members.*'],
            ['label' => __('messages.courses'), 'route' => 'admin.courses.index', 'active' => 'admin.courses.*'],
            ['label' => __('messages.attendance'), 'route' => 'admin.attendance.index', 'active' => 'admin.attendance.*'],
        ],
        'coach' => [
            ['label' => __('messages.dashboard'), 'route' => 'coach.dashboard', 'active' => 'coach.dashboard'],
            ['label' => __('messages.classes'), 'route' => 'coach.classes.index', 'active' => 'coach.classes.*'],
            ['label' => __('messages.members'), 'route' => 'coach.members.index', 'active' => 'coach.members.*'],
            ['label' => __('messages.progress'), 'route' => 'coach.progress.index', 'active' => 'coach.progress.*'],
        ],
        default => [
            ['label' => __('messages.dashboard'), 'route' => 'member.dashboard', 'active' => 'member.dashboard'],
            ['label' => __('messages.qr_code'), 'route' => 'member.qr-code', 'active' => 'member.qr-code'],
            ['label' => __('messages.courses'), 'route' => 'member.courses.index', 'active' => 'member.courses.*'],
            ['label' => __('messages.progress'), 'route' => 'member.progress', 'active' => 'member.progress'],
        ],
    };
@endphp

<nav class="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-white px-2 py-2 dark:border-slate-800 dark:bg-slate-900 lg:hidden">
    <div class="grid grid-cols-4 gap-1">
        @foreach($items as $item)
            @php($active = request()->routeIs($item['active']))
            <a href="{{ route($item['route']) }}" class="{{ $active ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-200' : 'text-slate-500 dark:text-slate-400' }} rounded-lg px-2 py-2 text-center text-xs font-bold transition">
                <span class="mx-auto mb-1 grid size-6 place-items-center rounded-full border border-current text-[10px]">{{ strtoupper(substr($item['label'], 0, 1)) }}</span>
                <span class="block truncate">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
