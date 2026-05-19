<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.analytics') }}</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-3">
            <x-stat-card :label="__('messages.reservations')" :value="$totalReservations" />
            <x-stat-card :label="__('messages.courses')" :value="$totalCourses" />
            <x-stat-card :label="__('messages.paid_subscription_volume')" :value="format_currency($paidSubscriptions)" />
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-chart-card :title="__('messages.plans_mix')">
                <div class="igym-chart-frame">
                    <canvas id="plansChart"></canvas>
                </div>
            </x-chart-card>
            <x-chart-card :title="__('messages.customer_status')">
                <div class="igym-chart-frame">
                    <canvas id="statusChart"></canvas>
                </div>
            </x-chart-card>
        </div>

        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60">
                <tr>
                    <th class="px-4 py-3 text-start font-bold">{{ __('messages.gyms') }}</th>
                    <th class="px-4 py-3 text-start font-bold">{{ __('messages.members') }}</th>
                    <th class="px-4 py-3 text-start font-bold">{{ __('messages.courses') }}</th>
                    <th class="px-4 py-3 text-start font-bold">{{ __('messages.reservations') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($topGyms as $gym)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $gym->name }}</td>
                        <td class="px-4 py-3">{{ $gym->members_count }}</td>
                        <td class="px-4 py-3">{{ $gym->courses_count }}</td>
                        <td class="px-4 py-3">{{ $gym->reservations_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </x-table>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.igymChart('plansChart', { type: 'bar', data: { labels: @json($plansChart['labels']), datasets: [{ data: @json($plansChart['data']), backgroundColor: '#F59E0B', borderRadius: 8 }] }, options: { plugins: { legend: { display: false } } } });
            window.igymChart('statusChart', { type: 'pie', data: { labels: @json($statusChart['labels']), datasets: [{ data: @json($statusChart['data']), backgroundColor: ['#22C55E', '#FACC15', '#EF4444', '#64748B'] }] } });
        });
    </script>
</x-app-layout>
