<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.members') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($members as $member)
                <div class="igym-card p-5"><p class="font-black">{{ $member->name }}</p><p class="text-sm text-slate-500">{{ $member->email }}</p><div class="mt-4 flex items-center justify-between"><x-badge :status="$member->activeSubscription?->status ?? 'expired'">{{ $member->activeSubscription?->plan_name ?? 'No active plan' }}</x-badge><span class="text-xs text-slate-500">{{ $member->progressEntries->first()?->recorded_at?->format('M d') ?? 'No progress' }}</span></div></div>
            @endforeach
        </div>
        <div class="mt-5">{{ $members->links() }}</div>
    </div>
</x-app-layout>
