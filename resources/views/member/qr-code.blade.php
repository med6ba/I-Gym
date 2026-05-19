<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.qr_code') }}</h2></x-slot>
    <div class="mx-auto max-w-3xl space-y-5 px-4 py-6 text-center sm:px-6 lg:px-8">
        <x-qr-card :payload="$payload" :qr-code="$qrCode" />
        <p class="text-sm text-slate-500 dark:text-slate-400">Show this code at reception or to your coach for QR access simulation.</p>
    </div>
</x-app-layout>
