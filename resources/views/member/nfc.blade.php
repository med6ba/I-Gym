<x-app-layout>
    <x-slot name="title">{{ __('messages.nfc_access') }}</x-slot>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.nfc_access') }}</h2></x-slot>
    <div class="mx-auto max-w-3xl space-y-5 px-4 py-6 text-center sm:px-6 lg:px-8">
        @if($hasActiveSubscription)
            <div class="igym-card mx-auto max-w-sm p-8">
                @if(auth()->user()->hasBracelet())
                    <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">
                        <x-icon name="nfc" size="26" />
                    </span>
                    <h2 class="mt-5 text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.bracelet_connected') }}</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ __('messages.bracelet_connected_hint') }}</p>
                    <x-badge status="success" class="mt-5">{{ __('messages.bracelet_assigned') }}</x-badge>
                @else
                    <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                        <x-icon name="nfc" size="26" />
                    </span>
                    <h2 class="mt-5 text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.no_bracelet') }}</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ __('messages.no_bracelet_hint') }}</p>
                    <x-badge status="warning" class="mt-5">{{ __('messages.contact_reception') }}</x-badge>
                @endif
            </div>
        @else
            <x-alert type="warning">{{ __('messages.subscription_required') }}</x-alert>
        @endif
    </div>
</x-app-layout>
