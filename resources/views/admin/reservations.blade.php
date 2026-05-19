<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.reservations') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">Member</th><th class="px-4 py-3 text-start">Course</th><th class="px-4 py-3 text-start">Status</th><th class="px-4 py-3 text-end">Update</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($reservations as $reservation)
                    <tr>
                        <td class="px-4 py-3 font-bold">{{ $reservation->member->name }}</td>
                        <td class="px-4 py-3">{{ $reservation->course->title }}<span class="block text-xs text-slate-500">{{ $reservation->course->starts_at->format('M d, H:i') }}</span></td>
                        <td class="px-4 py-3"><x-badge :status="$reservation->status" /></td>
                        <td class="px-4 py-3 text-end"><form method="POST" action="{{ route('admin.reservations.update', $reservation) }}" class="inline-flex gap-2">@csrf @method('PATCH')<select name="status" class="rounded-lg border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950">@foreach(['reserved','cancelled','attended','no_show'] as $status)<option value="{{ $status }}" @selected($reservation->status===$status)>{{ Str::headline($status) }}</option>@endforeach</select><button class="font-bold text-amber-600">Save</button></form></td>
                    </tr>
                @endforeach
            </tbody>
        </x-table>
        {{ $reservations->links() }}
    </div>
</x-app-layout>
