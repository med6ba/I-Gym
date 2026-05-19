<x-app-layout>
    <x-slot name="title">{{ __('messages.qr_code') }}</x-slot>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.qr_code') }}</h2></x-slot>
    <div
        class="mx-auto max-w-3xl space-y-5 px-4 py-6 text-center sm:px-6 lg:px-8"
        x-data="{
            canShowQr: window.matchMedia('(pointer: coarse)').matches || /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent),
            loading: true,
            qrCode: '',
            payload: '',
            loadQr() {
                if (!this.canShowQr || !@js($hasActiveSubscription)) {
                    this.loading = false;
                    return;
                }

                fetch(@js(route('member.qr-code.code')), { headers: { Accept: 'application/json' } })
                    .then((response) => response.ok ? response.json() : Promise.reject())
                    .then((data) => {
                        this.qrCode = data.qrCode;
                        this.payload = data.payload;
                    })
                    .finally(() => this.loading = false);
            }
        }"
        x-init="loadQr()"
    >
        @if($hasActiveSubscription)
            <div x-cloak x-show="canShowQr">
                <div class="mx-auto max-w-sm rounded-xl border border-slate-200 bg-white p-5 text-center dark:border-slate-800 dark:bg-slate-900">
                    <div class="mx-auto inline-block min-h-[292px] rounded-xl bg-white p-4 text-slate-950">
                        <div x-show="loading" class="grid min-h-[260px] place-items-center text-sm font-bold text-slate-500">{{ __('messages.loading_qr') }}</div>
                        <div x-show="!loading" x-html="qrCode"></div>
                    </div>
                    <p x-show="payload" x-text="payload" class="mt-4 break-all rounded-lg bg-slate-100 px-3 py-2 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300"></p>
                </div>
                <p class="mt-5 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.show_qr_help') }}</p>
            </div>
        @else
            <x-alert type="warning">{{ __('messages.subscription_required') }}</x-alert>
        @endif

        @if($hasActiveSubscription)
            <div x-cloak x-show="! canShowQr">
                <div class="igym-card mx-auto max-w-xl p-8">
                    <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300">
                        <x-icon name="phone" size="26" />
                    </span>
                    <h2 class="mt-5 text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.qr_mobile_only_title') }}</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ __('messages.qr_mobile_only_body') }}</p>
                    <x-badge status="warning" class="mt-5">{{ __('messages.mobile_or_tablet_required') }}</x-badge>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
