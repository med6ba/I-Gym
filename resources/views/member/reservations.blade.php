<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.reservations') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($reservations as $reservation)
                @php($cancelModal = 'cancel-reservation-'.$reservation->id)
                <div class="igym-card p-5">
                    <div class="flex justify-between gap-3"><div><p class="font-black">{{ $reservation->course->title }}</p><p class="text-sm text-slate-500">{{ $reservation->course->starts_at->format('M d, H:i') }} · {{ $reservation->course->coach->name }}</p></div><x-badge :status="$reservation->status" /></div>
                    @if($reservation->status === 'reserved')
                        <button type="button" class="igym-action igym-action-danger mt-4" x-on:click="$dispatch('open-modal', '{{ $cancelModal }}')">
                            <x-icon name="trash" size="16" />
                            {{ __('messages.cancel_reservation') }}
                        </button>
                    @endif
                </div>

                @if($reservation->status === 'reserved')
                    <x-modal name="{{ $cancelModal }}" maxWidth="md">
                        <form method="POST" action="{{ route('member.reservations.cancel', $reservation) }}" class="space-y-5">
                            @csrf
                            @method('PATCH')

                            <div>
                                <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.cancel_reservation') }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $reservation->course->title }} · {{ $reservation->course->starts_at->format('M d, H:i') }}</p>
                            </div>

                            <div class="flex justify-end gap-3">
                                <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', '{{ $cancelModal }}')">{{ __('messages.cancel') }}</x-button>
                                <x-button variant="danger" class="gap-2">
                                    <x-icon name="trash" size="16" />
                                    {{ __('messages.cancel_reservation') }}
                                </x-button>
                            </div>
                        </form>
                    </x-modal>
                @endif
            @endforeach
        </div>
        {{ $reservations->links() }}
    </div>
</x-app-layout>
