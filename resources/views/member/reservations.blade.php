<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.reservations') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($reservations as $reservation)
                <div class="igym-card p-5"><div class="flex justify-between gap-3"><div><p class="font-black">{{ $reservation->course->title }}</p><p class="text-sm text-slate-500">{{ $reservation->course->starts_at->format('M d, H:i') }} · {{ $reservation->course->coach->name }}</p></div><x-badge :status="$reservation->status" /></div>@if($reservation->status === 'reserved')<form method="POST" action="{{ route('member.reservations.cancel', $reservation) }}" class="mt-4">@csrf @method('PATCH')<button class="text-sm font-bold text-rose-600">{{ __('messages.cancel_reservation') }}</button></form>@endif</div>
            @endforeach
        </div>
        {{ $reservations->links() }}
    </div>
</x-app-layout>
