<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.dashboard') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        @if($subscription?->status !== 'active' || $subscription?->ends_at?->isPast())
            <x-alert type="warning">{{ __('messages.subscription_required') }}</x-alert>
        @elseif($subscription->ends_at->diffInDays(now()) <= 7)
            <x-alert type="warning">{{ __('messages.expiring_subscriptions') }}: {{ $subscription->ends_at->diffForHumans() }}.</x-alert>
        @endif
        <div class="grid gap-4 md:grid-cols-3">
            <x-stat-card :label="__('messages.subscription')" :value="Str::headline($subscription?->status ?? 'none')" :detail="\App\Models\Subscription::labelForPlan($subscription?->plan_name)" />
            <x-stat-card :label="__('messages.next_class')" :value="$nextReservation?->course?->title ?? __('messages.no_data')" :detail="$nextReservation?->course?->starts_at?->format('M d, H:i')" />
            <x-stat-card :label="__('messages.latest_weight')" value="{{ $latestProgress?->weight ? $latestProgress->weight.' kg' : __('messages.no_data') }}" />
        </div>
        <div class="grid gap-6">
            <x-chart-card :title="__('messages.progress_summary')">
                <div class="igym-chart-frame">
                    <canvas id="progressChart"></canvas>
                </div>
            </x-chart-card>
        </div>
        <x-chart-card :title="__('messages.notifications')">
            <div class="grid gap-3 md:grid-cols-2">
                @foreach($notifications as $notification)
                    <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800"><div class="flex justify-between gap-3"><p class="font-bold">{{ $notification->title }}</p><x-badge :status="$notification->type" /></div><p class="mt-2 text-sm text-slate-500">{{ $notification->message }}</p></div>
                @endforeach
            </div>
        </x-chart-card>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.igymChart('progressChart', { type: 'line', data: { labels: @json($progressChart['labels']), datasets: [{ label: @js(__('messages.weight')), data: @json($progressChart['data']), borderColor: '#F59E0B', backgroundColor: 'rgba(245,158,11,.15)', fill: true, tension: .35 }] } });
        });
    </script>
</x-app-layout>
