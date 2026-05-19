<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.subscription') }}</h2></x-slot>
    <div class="mx-auto max-w-5xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if($current)
            <div class="igym-card p-6"><div class="flex flex-wrap items-start justify-between gap-4"><div><p class="text-sm text-slate-500">Current plan</p><h3 class="text-2xl font-black">{{ $current->plan_name }}</h3><p class="mt-2 text-sm text-slate-500">{{ $current->starts_at->format('M d, Y') }} - {{ $current->ends_at->format('M d, Y') }}</p></div><x-badge :status="$current->status" /></div></div>
        @endif
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">Plan</th><th class="px-4 py-3 text-start">Price</th><th class="px-4 py-3 text-start">Ends</th><th class="px-4 py-3 text-start">Payment</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">@foreach($subscriptions as $subscription)<tr><td class="px-4 py-3 font-bold">{{ $subscription->plan_name }}</td><td class="px-4 py-3">{{ $subscription->price }}</td><td class="px-4 py-3">{{ $subscription->ends_at->format('M d, Y') }}</td><td class="px-4 py-3"><x-badge :status="$subscription->payment_status" /></td></tr>@endforeach</tbody>
        </x-table>
    </div>
</x-app-layout>
