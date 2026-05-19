<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.notifications') }}</h2></x-slot>
    <div class="mx-auto max-w-5xl space-y-4 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @foreach($notifications as $notification)
            <div class="igym-card p-5"><div class="flex justify-between gap-3"><p class="font-black">{{ $notification->title }}</p><x-badge :status="$notification->type" /></div><p class="mt-2 text-sm text-slate-500">{{ $notification->message }}</p>@if($notification->user_id === auth()->id() && ! $notification->is_read)<form method="POST" action="{{ route('member.notifications.read', $notification) }}" class="mt-3">@csrf @method('PATCH')<button class="text-sm font-bold text-amber-600">Mark read</button></form>@endif</div>
        @endforeach
        {{ $notifications->links() }}
    </div>
</x-app-layout>
