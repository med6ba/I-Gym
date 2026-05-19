<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.attendance') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="flex justify-end">
            <x-button type="button" class="gap-2" x-on:click="$dispatch('open-modal', 'record-attendance')">
                <x-icon name="plus" size="17" />
                {{ __('messages.mark_present') }}
            </x-button>
        </div>

        <x-modal name="record-attendance" :show="old('_modal') === 'record-attendance'" maxWidth="xl">
            <form method="POST" action="{{ route('admin.attendance.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="_modal" value="record-attendance">
                <input type="hidden" name="method" value="qr">

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.mark_present') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.attendance') }}</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.member') }}</span>
                        <select name="member_id" class="igym-input" required>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @selected((int) (old('_modal') === 'record-attendance' ? old('member_id') : 0) === $member->id)>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.courses') }}</span>
                        <select name="course_id" class="igym-input">
                            <option value="">{{ __('messages.gym_access_only') }}</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" @selected((int) (old('_modal') === 'record-attendance' ? old('course_id') : 0) === $course->id)>{{ $course->title }} · {{ $course->starts_at->format('M d') }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'record-attendance')">{{ __('messages.cancel') }}</x-button>
                    <x-button>{{ __('messages.mark_present') }}</x-button>
                </div>
            </form>
        </x-modal>

        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.member') }}</th><th class="px-4 py-3 text-start">{{ __('messages.courses') }}</th><th class="px-4 py-3 text-start">{{ __('messages.method') }}</th><th class="px-4 py-3 text-start">{{ __('messages.time') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">@foreach($attendances as $attendance)<tr><td class="px-4 py-3 font-bold">{{ $attendance->member->name }}</td><td class="px-4 py-3">{{ $attendance->course?->title ?? __('messages.gym_access_only') }}</td><td class="px-4 py-3"><x-badge :status="$attendance->method" /></td><td class="px-4 py-3">{{ $attendance->check_in_time->format('M d, H:i') }}</td></tr>@endforeach</tbody>
        </x-table>
        {{ $attendances->links() }}
    </div>
</x-app-layout>
