<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.notifications') }}</h2></x-slot>
    <div class="mx-auto max-w-5xl space-y-4 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        <div class="igym-card overflow-hidden">
            @foreach($notifications as $notification)
                <div class="{{ ! $notification->is_read && $notification->user_id === auth()->id() ? 'bg-amber-50/60 dark:bg-amber-950/10' : '' }} flex gap-4 border-b border-slate-100 p-4 last:border-b-0 dark:border-slate-800">
                    <span class="relative grid size-11 shrink-0 place-items-center rounded-xl bg-slate-100 text-amber-600 dark:bg-slate-800 dark:text-amber-300">
                        <x-icon name="inbox" size="19" />
                        @if(! $notification->is_read && $notification->user_id === auth()->id())
                            <span class="absolute -end-1 -top-1 size-3 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900"></span>
                        @endif
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <p class="font-black text-slate-950 dark:text-white">{{ $notification->title }}</p>
                            <x-badge :status="$notification->type" />
                        </div>
                        <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $notification->message }}</p>
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                            <p class="text-xs font-semibold text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                            @if($notification->user_id === auth()->id() && ! $notification->is_read)
                                <form method="POST" action="{{ route('member.notifications.read', $notification) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm font-black text-amber-600 hover:text-amber-500">{{ __('messages.mark_read') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $notifications->links() }}
    </div>
</x-app-layout>
