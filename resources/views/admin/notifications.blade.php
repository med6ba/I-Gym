<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.notifications') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="flex justify-end">
            <x-button type="button" class="gap-2" x-on:click="$dispatch('open-modal', 'send-notification')">
                <x-icon name="plus" size="17" />
                {{ __('messages.send_notification') }}
            </x-button>
        </div>

        <x-modal name="send-notification" :show="old('_modal') === 'send-notification'" maxWidth="xl">
            <form method="POST" action="{{ route('admin.notifications.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="_modal" value="send-notification">

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.send_notification') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.notifications') }}</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.member') }}</span>
                        <select name="user_id" class="igym-input">
                            <option value="">{{ __('messages.all_members') }}</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @selected((int) (old('_modal') === 'send-notification' ? old('user_id') : 0) === $member->id)>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.status') }}</span>
                        <select name="type" class="igym-input">
                            @foreach(['info','warning','success','danger'] as $type)
                                <option value="{{ $type }}" @selected((old('_modal') === 'send-notification' ? old('type') : 'info') === $type)>{{ Str::headline($type) }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.title') }}</span>
                        <input name="title" value="{{ old('_modal') === 'send-notification' ? old('title') : '' }}" placeholder="Class update" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.message') }}</span>
                        <input name="message" value="{{ old('_modal') === 'send-notification' ? old('message') : '' }}" placeholder="Your class starts in 30 minutes" class="igym-input" required>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'send-notification')">{{ __('messages.cancel') }}</x-button>
                    <x-button>{{ __('messages.send_notification') }}</x-button>
                </div>
            </form>
        </x-modal>

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
