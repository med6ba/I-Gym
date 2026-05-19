<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.dashboard') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-3">
            <x-stat-card :label="__('messages.today_classes')" :value="$todayClasses->count()" />
            <x-stat-card :label="__('messages.assigned_members')" :value="$assignedMembers" />
            <x-stat-card :label="__('messages.attendance_rate')" value="{{ $attendanceRate }}%" />
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
            <x-chart-card :title="__('messages.upcoming_sessions')">
                <div class="space-y-3">
                    @forelse($upcomingSessions as $course)
                        <a href="{{ route('coach.classes.attendance', $course) }}" class="block rounded-xl border border-slate-200 p-4 transition hover:border-amber-300 hover:bg-amber-50/40 dark:border-slate-800 dark:hover:bg-amber-950/20">
                            <div class="flex justify-between gap-3"><p class="font-bold">{{ $course->title }}</p><x-badge :status="$course->status" /></div>
                            <p class="mt-1 text-sm text-slate-500">{{ $course->starts_at->format('M d, H:i') }} · {{ $course->active_reservations_count }}/{{ $course->max_capacity }}</p>
                        </a>
                    @empty
                        <x-empty-state :message="__('messages.no_upcoming_sessions')" />
                    @endforelse
                </div>
            </x-chart-card>
            <x-chart-card :title="__('messages.members_needing_follow_up')">
                <div class="space-y-3">
                    @forelse($followUps as $member)
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800"><p class="font-bold">{{ $member->name }}</p><p class="text-sm text-slate-500">{{ $member->email }}</p></div>
                    @empty
                        <x-empty-state :message="__('messages.all_members_recent_progress')" />
                    @endforelse
                </div>
            </x-chart-card>
        </div>
    </div>
</x-app-layout>
