<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.reservations') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <div class="transition">
            <x-table>
                <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.member') }}</th><th class="px-4 py-3 text-start">{{ __('messages.courses') }}</th><th class="px-4 py-3 text-start">{{ __('messages.status') }}</th><th class="px-4 py-3 text-end">{{ __('messages.update') }}</th></tr></thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($reservations as $reservation)
                        @php($editModal = 'edit-reservation-'.$reservation->id)
                        <tr>
                            <td class="px-4 py-3 font-bold">{{ $reservation->member->name }}</td>
                            <td class="px-4 py-3">{{ $reservation->course->title }}<span class="block text-xs text-slate-500">{{ $reservation->course->starts_at->format('M d, H:i') }}</span></td>
                            <td class="px-4 py-3"><x-badge :status="$reservation->status" /></td>
                            <td class="px-4 py-3 text-end">
                                <button type="button" class="igym-action igym-action-edit" x-on:click="$dispatch('open-modal', '{{ $editModal }}')" title="{{ __('messages.edit') }}">
                                    <x-icon name="edit" size="16" />
                                    {{ __('messages.edit') }}
                                </button>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </x-table>

            @foreach($reservations as $reservation)
                @php($editModal = 'edit-reservation-'.$reservation->id)

                <x-modal name="{{ $editModal }}" :show="old('_modal') === $editModal" maxWidth="md">
                    <form method="POST" action="{{ route('admin.reservations.update', $reservation) }}" class="space-y-5">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="_modal" value="{{ $editModal }}">

                        <div>
                            <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.edit') }} {{ __('messages.reservation') }}</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $reservation->member->name }} · {{ $reservation->course->title }}</p>
                        </div>

                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.status') }}</span>
                            <select name="status" class="igym-input">
                                @foreach(['reserved','cancelled','attended','no_show'] as $status)
                                    <option value="{{ $status }}" @selected((old('_modal') === $editModal ? old('status') : $reservation->status) === $status)>{{ Str::headline($status) }}</option>
                                @endforeach
                            </select>
                        </label>

                        <div class="flex justify-end gap-3">
                            <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', '{{ $editModal }}')">{{ __('messages.cancel') }}</x-button>
                            <x-button>{{ __('messages.save') }}</x-button>
                        </div>
                    </form>
                </x-modal>
            @endforeach

            <div class="mt-5">{{ $reservations->links() }}</div>
        </div>
    </div>
</x-app-layout>
