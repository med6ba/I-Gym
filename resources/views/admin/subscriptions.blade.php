<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.subscriptions') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <form method="POST" action="{{ route('admin.subscriptions.store') }}" class="igym-card grid gap-4 p-5 lg:grid-cols-4">
            @csrf
            <select name="user_id" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach($members as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select>
            <input name="plan_name" value="Monthly Access" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="number" name="price" value="299" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <select name="payment_status" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"><option value="paid">{{ __('messages.paid') }}</option><option value="unpaid">{{ __('messages.unpaid') }}</option></select>
            <input type="date" name="starts_at" value="{{ now()->toDateString() }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="date" name="ends_at" value="{{ now()->addMonth()->toDateString() }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="hidden" name="status" value="active"><x-button>{{ __('messages.create_subscription') }}</x-button>
        </form>
        @if($expiring->isNotEmpty())<x-alert type="warning">{{ $expiring->count() }} subscriptions expire within 7 days.</x-alert>@endif
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.member') }}</th><th class="px-4 py-3 text-start">{{ __('messages.plan') }}</th><th class="px-4 py-3 text-start">{{ __('messages.price') }}</th><th class="px-4 py-3 text-start">{{ __('messages.ends') }}</th><th class="px-4 py-3 text-start">{{ __('messages.status') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">@foreach($subscriptions as $subscription)<tr><td class="px-4 py-3 font-bold">{{ $subscription->member->name }}</td><td class="px-4 py-3">{{ $subscription->plan_name }}</td><td class="px-4 py-3">{{ format_currency($subscription->price) }}</td><td class="px-4 py-3">{{ $subscription->ends_at->format('M d, Y') }}</td><td class="px-4 py-3"><x-badge :status="$subscription->status" /></td></tr>@endforeach</tbody>
        </x-table>
        {{ $subscriptions->links() }}
    </div>
</x-app-layout>
