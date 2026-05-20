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

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.mark_present') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.attendance') }}</p>
                </div>

                <div class="grid gap-4" x-data="{ method: 'nfc' }">
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.member') }}</span>
                        <select name="member_id" class="igym-input" required>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" data-bracelet="{{ $member->hasBracelet() ? '1' : '0' }}" @selected((int) (old('_modal') === 'record-attendance' ? old('member_id') : 0) === $member->id)>{{ $member->name }}@if($member->hasBracelet()) · 🏷️ {{ __('messages.bracelet_assigned') }}@endif</option>
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
                    <div>
                        <span class="igym-label">{{ __('messages.method') }}</span>
                        <div class="mt-1.5 flex gap-2">
                            <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold transition has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50 dark:border-slate-700 dark:has-[:checked]:border-amber-700 dark:has-[:checked]:bg-amber-950/30">
                                <input type="radio" name="method" value="nfc" x-model="method" class="size-4 accent-amber-500">
                                <x-icon name="nfc" size="18" class="text-amber-500" />
                                {{ __('messages.nfc') }}
                            </label>
                            <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold transition has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50 dark:border-slate-700 dark:has-[:checked]:border-amber-700 dark:has-[:checked]:bg-amber-950/30">
                                <input type="radio" name="method" value="manual" x-model="method" class="size-4 accent-amber-500">
                                <x-icon name="user" size="18" class="text-slate-400" />
                                {{ __('messages.manual') }}
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-slate-400" x-show="method === 'nfc'" x-cloak>{{ __('messages.nfc_attendance_hint') }}</p>
                        <p class="mt-1 text-xs text-slate-400" x-show="method === 'manual'" x-cloak>{{ __('messages.manual_attendance_hint') }}</p>
                    </div>
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
