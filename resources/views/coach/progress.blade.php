<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.progress') }}</h2></x-slot>
    @php($defaultProgressDate = now()->toDateString())
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="flex justify-end">
            <x-button type="button" class="gap-2" x-on:click="$dispatch('open-modal', 'record-progress')">
                <x-icon name="plus" size="17" />
                {{ __('messages.record_progress') }}
            </x-button>
        </div>

        <x-modal name="record-progress" :show="old('_modal') === 'record-progress'" maxWidth="2xl">
            <form method="POST" action="{{ route('coach.progress.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="_modal" value="record-progress">

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.record_progress') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.progress') }}</p>
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <label class="igym-field lg:col-span-2">
                        <span class="igym-label">{{ __('messages.member') }}</span>
                        <select name="member_id" class="igym-input" required>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @selected((int) (old('_modal') === 'record-progress' ? old('member_id') : 0) === $member->id)>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.weight') }}</span>
                        <input type="number" step=".1" name="weight" value="{{ old('_modal') === 'record-progress' ? old('weight') : '' }}" placeholder="72.5" class="igym-input">
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.body_fat') }} %</span>
                        <input type="number" step=".1" name="body_fat" value="{{ old('_modal') === 'record-progress' ? old('body_fat') : '' }}" placeholder="18.5" class="igym-input">
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.muscle_mass') }}</span>
                        <input type="number" step=".1" name="muscle_mass" value="{{ old('_modal') === 'record-progress' ? old('muscle_mass') : '' }}" placeholder="34.0" class="igym-input">
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.recorded') }}</span>
                        <input type="date" name="recorded_at" value="{{ old('_modal') === 'record-progress' ? old('recorded_at') : $defaultProgressDate }}" placeholder="YYYY-MM-DD" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.goal') }}</span>
                        <input name="goal" value="{{ old('_modal') === 'record-progress' ? old('goal') : '' }}" placeholder="Improve endurance" class="igym-input">
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.notes') }}</span>
                        <input name="notes" value="{{ old('_modal') === 'record-progress' ? old('notes') : '' }}" placeholder="Energy, soreness, or next focus" class="igym-input">
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'record-progress')">{{ __('messages.cancel') }}</x-button>
                    <x-button>{{ __('messages.record_progress') }}</x-button>
                </div>
            </form>
        </x-modal>

        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.member') }}</th><th class="px-4 py-3 text-start">{{ __('messages.weight') }}</th><th class="px-4 py-3 text-start">{{ __('messages.body_fat') }}</th><th class="px-4 py-3 text-start">{{ __('messages.recorded') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">@foreach($progressEntries as $entry)<tr><td class="px-4 py-3 font-bold">{{ $entry->member->name }}</td><td class="px-4 py-3">{{ $entry->weight }}</td><td class="px-4 py-3">{{ $entry->body_fat }}%</td><td class="px-4 py-3">{{ $entry->recorded_at->format('M d, Y') }}</td></tr>@endforeach</tbody>
        </x-table>
        {{ $progressEntries->links() }}
    </div>
</x-app-layout>
