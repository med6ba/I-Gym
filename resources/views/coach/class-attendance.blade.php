<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4"><div><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ $course->title }}</h2><p class="text-sm text-slate-500">{{ $course->starts_at->format('M d, H:i') }} · {{ $course->room }}</p></div><x-badge :status="$course->status" /></div>
    </x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <form method="POST" action="{{ route('coach.classes.attendance.store', $course) }}" class="igym-card grid gap-4 p-5 md:grid-cols-3">
            @csrf
            <select name="member_id" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach($members as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select>
            <select name="method" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"><option value="qr">QR</option><option value="manual">{{ __('messages.manual') }}</option></select>
            <x-button>{{ __('messages.mark_present') }}</x-button>
        </form>
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.member') }}</th><th class="px-4 py-3 text-start">{{ __('messages.reservation') }}</th><th class="px-4 py-3 text-start">{{ __('messages.attendance') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($course->reservations as $reservation)
                    @php($present = $course->attendances->firstWhere('user_id', $reservation->user_id))
                    <tr><td class="px-4 py-3 font-bold">{{ $reservation->member->name }}</td><td class="px-4 py-3"><x-badge :status="$reservation->status" /></td><td class="px-4 py-3">{{ $present ? $present->check_in_time->format('H:i').' · '.Str::upper($present->method) : 'Not checked in' }}</td></tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
</x-app-layout>
