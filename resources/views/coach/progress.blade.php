<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.progress') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <form method="POST" action="{{ route('coach.progress.store') }}" class="igym-card grid gap-4 p-5 lg:grid-cols-4">
            @csrf
            <select name="member_id" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach($members as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select>
            <input type="number" step=".1" name="weight" placeholder="Weight" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">
            <input type="number" step=".1" name="body_fat" placeholder="Body fat %" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">
            <input type="number" step=".1" name="muscle_mass" placeholder="Muscle mass" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">
            <input name="goal" placeholder="Goal" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">
            <input type="date" name="recorded_at" value="{{ now()->toDateString() }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input name="notes" placeholder="Notes" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 lg:col-span-2">
            <x-button>Record Progress</x-button>
        </form>
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">Member</th><th class="px-4 py-3 text-start">Weight</th><th class="px-4 py-3 text-start">Body Fat</th><th class="px-4 py-3 text-start">Recorded</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">@foreach($progressEntries as $entry)<tr><td class="px-4 py-3 font-bold">{{ $entry->member->name }}</td><td class="px-4 py-3">{{ $entry->weight }}</td><td class="px-4 py-3">{{ $entry->body_fat }}%</td><td class="px-4 py-3">{{ $entry->recorded_at->format('M d, Y') }}</td></tr>@endforeach</tbody>
        </x-table>
        {{ $progressEntries->links() }}
    </div>
</x-app-layout>
