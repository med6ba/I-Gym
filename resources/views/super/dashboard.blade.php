<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold uppercase text-amber-600">{{ __('messages.saas_command_center') }}</p>
                <h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.dashboard') }}</h2>
            </div>
            <a href="{{ route('super.gyms.create') }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 hover:bg-amber-400">{{ __('messages.new_gym') }}</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
            <x-stat-card :label="__('messages.gyms')" :value="$totalGyms" />
            <x-stat-card :label="__('messages.active_gyms')" :value="$activeGyms" />
            <x-stat-card :label="__('messages.trial_gyms')" :value="$trialGyms" />
            <x-stat-card :label="__('messages.expired_gyms')" :value="$expiredGyms" />
            <x-stat-card :label="__('messages.platform_users')" :value="$totalUsers" />
            <x-stat-card label="MRR" :value="format_currency($monthlyRevenue)" />
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-chart-card :title="__('messages.saas_growth')">
                <canvas id="growthChart" class="h-72 w-full"></canvas>
            </x-chart-card>
            <x-chart-card :title="__('messages.gym_status')">
                <canvas id="statusChart" class="h-72 w-full"></canvas>
            </x-chart-card>
        </div>

        <x-chart-card :title="__('messages.recent_gym_customers')">
            <div class="space-y-3">
                @foreach($recentGyms as $gym)
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                        <div>
                            <p class="font-bold text-slate-950 dark:text-white">{{ $gym->name }}</p>
                            <p class="text-sm text-slate-500">{{ $gym->city }} · {{ $gym->email }}</p>
                        </div>
                        <x-badge :status="$gym->status" />
                    </div>
                @endforeach
            </div>
        </x-chart-card>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            new Chart(document.getElementById('growthChart'), {
                type: 'line',
                data: { labels: @json($growthChart['labels']), datasets: [{ label: 'Gyms', data: @json($growthChart['data']), borderColor: '#F59E0B', backgroundColor: 'rgba(245, 158, 11, .15)', tension: .35, fill: true }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: { labels: @json($statusChart['labels']), datasets: [{ data: @json($statusChart['data']), backgroundColor: ['#22C55E', '#F59E0B', '#EF4444', '#64748B'] }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
</x-app-layout>
