<x-app-layout>
    <x-slot name="title">{{ $course->title }}</x-slot>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4"><div><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ $course->title }}</h2><p class="text-sm text-slate-500">{{ $course->starts_at->format('M d, H:i') }} · {{ $course->room }}</p></div><x-badge :status="$course->status" /></div>
    </x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="flex justify-end">
            <x-button type="button" class="gap-2" x-on:click="$dispatch('open-modal', 'mark-class-attendance')">
                <x-icon name="plus" size="17" />
                {{ __('messages.mark_present') }}
            </x-button>
        </div>

        <x-modal name="mark-class-attendance" :show="old('_modal') === 'mark-class-attendance'" maxWidth="xl">
            <form method="POST" action="{{ route('coach.classes.attendance.store', $course) }}" class="space-y-5">
                @csrf
                <input type="hidden" name="_modal" value="mark-class-attendance">

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.mark_present') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $course->title }}</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.member') }}</span>
                        <select name="member_id" class="igym-input" required>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @selected((int) (old('_modal') === 'mark-class-attendance' ? old('member_id') : 0) === $member->id)>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.method') }}</span>
                        <select name="method" class="igym-input">
                            <option value="nfc" @selected((old('_modal') === 'mark-class-attendance' ? old('method') : 'nfc') === 'nfc')>NFC</option>
                            <option value="manual" @selected((old('_modal') === 'mark-class-attendance' ? old('method') : 'nfc') === 'manual')>{{ __('messages.manual') }}</option>
                        </select>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'mark-class-attendance')">{{ __('messages.cancel') }}</x-button>
                    <x-button>{{ __('messages.mark_present') }}</x-button>
                </div>
            </form>
        </x-modal>

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
