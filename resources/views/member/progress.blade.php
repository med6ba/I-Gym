<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.progress') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-3"><x-stat-card :label="__('messages.weight')" value="{{ $latest?->weight ? $latest->weight.' kg' : __('messages.no_data') }}" /><x-stat-card :label="__('messages.body_fat')" value="{{ $latest?->body_fat ? $latest->body_fat.'%' : __('messages.no_data') }}" /><x-stat-card :label="__('messages.muscle_mass')" value="{{ $latest?->muscle_mass ? $latest->muscle_mass.' kg' : __('messages.no_data') }}" /></div>
        <div class="grid gap-6">
            <x-chart-card :title="__('messages.body_progress')">
                <div class="igym-chart-frame">
                    <canvas id="memberProgressChart"></canvas>
                </div>
            </x-chart-card>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.igymChart('memberProgressChart', { type: 'line', data: { labels: @json($progressChart['labels']), datasets: [{ label: @js(__('messages.weight')), data: @json($progressChart['weight']), borderColor: '#F59E0B', tension: .35 }, { label: @js(__('messages.body_fat')), data: @json($progressChart['bodyFat']), borderColor: '#FB923C', tension: .35 }] } });
        });
    </script>
</x-app-layout>
