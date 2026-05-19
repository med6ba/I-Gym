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
        <div class="grid gap-3 md:grid-cols-2">
            @foreach($notifications as $notification)
                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800"><div class="flex justify-between gap-3"><p class="font-bold">{{ $notification->title }}</p><x-badge :status="$notification->type" /></div><p class="mt-2 text-sm text-slate-500">{{ $notification->message }}</p><p class="mt-2 text-xs text-slate-400">{{ $notification->user?->name ?? 'Gym-wide' }}</p></div>
            @endforeach
        </div>
        {{ $notifications->links() }}
    </div>
</x-app-layout>
