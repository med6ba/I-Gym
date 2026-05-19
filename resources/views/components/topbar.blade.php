@php
    $user = auth()->user();
    $pageTitle = igym_current_page_title($user);
    $notificationRoute = igym_notification_route($user);
    $unreadNotifications = igym_unread_notification_count($user);
    $quickActions = match ($user->role) {
        'super_admin' => [
            ['label' => __('messages.manage_gyms_admins'), 'route' => 'super.gyms.index', 'icon' => 'building'],
        ],
        'gym_admin' => [
            ['label' => __('messages.create_course'), 'route' => 'admin.courses.index', 'icon' => 'calendar'],
            ['label' => __('messages.attendance'), 'route' => 'admin.attendance.index', 'icon' => 'qr'],
            ['label' => __('messages.notifications'), 'route' => 'admin.notifications.index', 'icon' => 'bell'],
        ],
        'coach' => [
            ['label' => __('messages.classes'), 'route' => 'coach.classes.index', 'icon' => 'calendar'],
            ['label' => __('messages.training_plans'), 'route' => 'coach.training-plans.index', 'icon' => 'target'],
            ['label' => __('messages.progress'), 'route' => 'coach.progress.index', 'icon' => 'activity'],
        ],
        'reception' => [
            ['label' => __('messages.reception_scanner'), 'route' => 'reception.scanner', 'icon' => 'scan'],
        ],
        default => [
            ['label' => __('messages.book_class'), 'route' => 'member.courses.index', 'icon' => 'calendar'],
            ['label' => __('messages.subscription'), 'route' => 'member.subscription', 'icon' => 'credit-card'],
        ],
    };
@endphp

<div class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95" x-data="{ quick: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <div class="min-w-0">
            <p class="truncate text-sm font-bold text-amber-600 dark:text-amber-300">{{ $user->gym?->name ?? __('messages.global_saas') }}</p>
            <h1 class="truncate text-lg font-black text-slate-950 dark:text-white">{{ $pageTitle }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <div class="relative hidden md:block">
                <button type="button" x-on:click="quick = ! quick" class="igym-focus inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30">
                    <x-icon name="sparkles" size="17" class="text-amber-500" />
                    {{ __('messages.quick') }}
                    <x-icon name="chevron-down" size="15" />
                </button>
                <div x-show="quick" x-cloak x-transition x-on:click.outside="quick = false" class="absolute end-0 mt-2 w-64 rounded-xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900">
                    @foreach($quickActions as $action)
                        <a href="{{ route($action['route']) }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-amber-50 hover:text-amber-800 dark:text-slate-200 dark:hover:bg-amber-950/30 dark:hover:text-amber-200">
                            <span class="igym-menu-icon"><x-icon name="{{ $action['icon'] }}" size="18" /></span>
                            {{ $action['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($notificationRoute)
                    <a href="{{ $notificationRoute }}" class="igym-focus relative grid size-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30" title="{{ __('messages.notifications') }}">
                        <x-icon name="inbox" size="18" />
                        @if($unreadNotifications > 0)
                            <span class="absolute -end-1 -top-1 flex size-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white ring-2 ring-white dark:ring-slate-900">{{ $unreadNotifications }}</span>
                            <span class="absolute end-1 top-1 size-2 rounded-full bg-rose-500 ring-2 ring-white motion-safe:animate-ping dark:ring-slate-900"></span>
                        @endif
                    </a>
                @endif
                <a href="{{ route('settings.index') }}" class="igym-focus grid size-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30" title="{{ __('messages.settings') }}">
                    <x-icon name="settings" size="18" />
                </a>
            </div>
        </div>
    </div>
</div>
