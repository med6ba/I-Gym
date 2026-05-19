<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.attendance') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <form method="POST" action="{{ route('admin.attendance.store') }}" class="igym-card grid gap-4 p-5 md:grid-cols-4">
            @csrf
            <select name="member_id" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach($members as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select>
            <select name="course_id" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"><option value="">{{ __('messages.gym_access_only') }}</option>@foreach($courses as $course)<option value="{{ $course->id }}">{{ $course->title }} · {{ $course->starts_at->format('M d') }}</option>@endforeach</select>
            <select name="method" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"><option value="qr">QR</option><option value="manual">{{ __('messages.manual') }}</option></select>
            <x-button>{{ __('messages.mark_present') }}</x-button>
        </form>
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.member') }}</th><th class="px-4 py-3 text-start">{{ __('messages.courses') }}</th><th class="px-4 py-3 text-start">{{ __('messages.method') }}</th><th class="px-4 py-3 text-start">{{ __('messages.time') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">@foreach($attendances as $attendance)<tr><td class="px-4 py-3 font-bold">{{ $attendance->member->name }}</td><td class="px-4 py-3">{{ $attendance->course?->title ?? 'Gym access' }}</td><td class="px-4 py-3"><x-badge :status="$attendance->method" /></td><td class="px-4 py-3">{{ $attendance->check_in_time->format('M d, H:i') }}</td></tr>@endforeach</tbody>
        </x-table>
        {{ $attendances->links() }}
    </div>
</x-app-layout>
