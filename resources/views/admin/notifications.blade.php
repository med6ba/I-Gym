<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.notifications') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        <form method="POST" action="{{ route('admin.notifications.store') }}" class="igym-card grid gap-4 p-5 lg:grid-cols-4">
            @csrf
            <select name="user_id" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"><option value="">{{ __('messages.all_members') }}</option>@foreach($members as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select>
            <select name="type" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach(['info','warning','success','danger'] as $type)<option value="{{ $type }}">{{ Str::headline($type) }}</option>@endforeach</select>
            <input name="title" placeholder="{{ __('messages.title') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input name="message" placeholder="{{ __('messages.message') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <x-button>{{ __('messages.send_notification') }}</x-button>
        </form>
        <div class="igym-card overflow-hidden">
            @foreach($notifications as $notification)
                <div class="flex gap-4 border-b border-slate-100 p-4 last:border-b-0 dark:border-slate-800">
                    <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-slate-100 text-amber-600 dark:bg-slate-800 dark:text-amber-300">
                        <x-icon name="inbox" size="19" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <p class="font-black text-slate-950 dark:text-white">{{ $notification->title }}</p>
                            <x-badge :status="$notification->type" />
                        </div>
                        <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $notification->message }}</p>
                        <p class="mt-2 text-xs font-semibold text-slate-400">{{ $notification->user?->name ?? __('messages.gym_wide') }} · {{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $notifications->links() }}
    </div>
</x-app-layout>
