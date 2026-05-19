<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.dashboard') }}</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
            <x-stat-card :label="__('messages.active_members')" :value="$activeMembers" />
            <x-stat-card :label="__('messages.active_coaches')" :value="$activeCoaches" />
            <x-stat-card :label="__('messages.today_classes')" :value="$todayClasses" />
            <x-stat-card :label="__('messages.reservations_today')" :value="$reservationsToday" />
            <x-stat-card :label="__('messages.occupancy_rate')" value="{{ $occupancyRate }}%" />
            <x-stat-card :label="__('messages.no_shows')" :value="$noShows" />
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_.8fr]">
            <x-chart-card :title="__('messages.attendance_trend')"><canvas id="attendanceChart" class="h-72 w-full"></canvas></x-chart-card>
            <x-chart-card title="{{ __('messages.smart_alerts') }}">
                <div class="space-y-3">
                    @foreach($smartAlerts as $alert)
                        <x-alert type="info">{{ $alert }}</x-alert>
                    @endforeach
                </div>
            </x-chart-card>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-chart-card :title="__('messages.popular_classes')"><canvas id="popularChart" class="h-72 w-full"></canvas></x-chart-card>
            <x-chart-card :title="__('messages.expiring_subscriptions')">
                <div class="space-y-3">
                    @forelse($expiringSubscriptions as $subscription)
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 p-3 dark:border-slate-800">
                            <div>
                                <p class="font-bold">{{ $subscription->member->name }}</p>
                                <p class="text-sm text-slate-500">{{ $subscription->ends_at->format('M d, Y') }}</p>
                            </div>
                            <x-badge status="warning">{{ $subscription->ends_at->diffForHumans() }}</x-badge>
                        </div>
                    @empty
                        <x-empty-state :message="__('messages.no_renewals_this_week')" />
                    @endforelse
                </div>
            </x-chart-card>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            new Chart(document.getElementById('attendanceChart'), { type: 'line', data: { labels: @json($attendanceChart['labels']), datasets: [{ label: 'Check-ins', data: @json($attendanceChart['data']), borderColor: '#F59E0B', backgroundColor: 'rgba(245,158,11,.15)', fill: true, tension: .35 }] }, options: { responsive: true, maintainAspectRatio: false } });
            new Chart(document.getElementById('popularChart'), { type: 'bar', data: { labels: @json($popularClassesChart['labels']), datasets: [{ data: @json($popularClassesChart['data']), backgroundColor: '#FB923C' }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } } });
        });
    </script>
</x-app-layout>
