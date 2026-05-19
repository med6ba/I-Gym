<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.progress') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-3"><x-stat-card label="Weight" value="{{ $latest?->weight ? $latest->weight.' kg' : 'No data' }}" /><x-stat-card label="Body Fat" value="{{ $latest?->body_fat ? $latest->body_fat.'%' : 'No data' }}" /><x-stat-card label="Muscle Mass" value="{{ $latest?->muscle_mass ? $latest->muscle_mass.' kg' : 'No data' }}" /></div>
        <div class="grid gap-6 lg:grid-cols-[1fr_.8fr]"><x-chart-card title="Body Progress"><canvas id="memberProgressChart" class="h-72 w-full"></canvas></x-chart-card><x-chart-card :title="__('messages.ai_recommendations')"><p class="font-black">{{ $recommendation['title'] }}</p><p class="mt-2 text-sm text-slate-500">{{ $recommendation['reason'] }}</p></x-chart-card></div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            new Chart(document.getElementById('memberProgressChart'), { type: 'line', data: { labels: @json($progressChart['labels']), datasets: [{ label: 'Weight', data: @json($progressChart['weight']), borderColor: '#F59E0B' }, { label: 'Body fat', data: @json($progressChart['bodyFat']), borderColor: '#FB923C' }] }, options: { responsive: true, maintainAspectRatio: false } });
        });
    </script>
</x-app-layout>
