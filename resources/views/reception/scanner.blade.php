<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.nfc_reader') }}</h2>
        </div>
    </x-slot>

    <div class="mx-auto flex flex-col items-center justify-center px-4 py-8 sm:px-6 lg:px-8" x-data='nfcReader()' x-init="init()">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="relative w-full max-w-md">
            <div class="mb-6 text-start">
                <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">{{ __('messages.nfc_demo_hint') }}</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-2xl shadow-slate-950/10 dark:border-slate-700 dark:bg-slate-900">
                <div class="relative mx-auto mb-6">
                    <div class="relative mx-auto flex size-48 items-center justify-center">
                        <div class="absolute inset-0 rounded-full border-4 border-amber-200 dark:border-amber-800" x-bind:class="scanning ? 'animate-ping border-amber-400' : ''"></div>
                        <div class="absolute inset-2 rounded-full border-2 border-amber-100 dark:border-amber-900" x-bind:class="scanning ? 'animate-pulse' : ''"></div>
                        <div class="relative z-10 flex size-40 items-center justify-center rounded-full" x-bind:class="statusClass">
                            <template x-if="!result && !scanning">
                                <x-icon name="scan" size="56" class="text-amber-500" />
                            </template>
                            <template x-if="scanning && !result">
                                <svg class="size-14 text-amber-500 motion-safe:animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56" stroke-linecap="round" />
                                </svg>
                            </template>
                            <template x-if="result === 'allowed'">
                                <svg class="size-14 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="m20 6-11 11-5-5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </template>
                            <template x-if="result === 'warning'">
                                <svg class="size-14 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M12 9v4M12 17h.01" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </template>
                            <template x-if="result === 'denied'">
                                <svg class="size-14 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M18 6 6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </template>
                        </div>
                    </div>
                </div>

                <h2 class="text-xl font-black text-slate-950 dark:text-white" x-text="titleText"></h2>
                <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400" x-text="bodyText"></p>

                <div x-show="result" x-cloak x-transition class="mt-6 space-y-3">
                    <div class="rounded-xl border p-4 text-start text-sm" x-bind:class="result === 'allowed' ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-900 dark:bg-emerald-950/30' : result === 'warning' ? 'border-amber-200 bg-amber-50 dark:border-amber-900 dark:bg-amber-950/30' : 'border-rose-200 bg-rose-50 dark:border-rose-900 dark:bg-rose-950/30'">
                        <p class="font-bold" x-bind:class="result === 'allowed' ? 'text-emerald-800 dark:text-emerald-200' : result === 'warning' ? 'text-amber-800 dark:text-amber-200' : 'text-rose-800 dark:text-rose-200'" x-text="detailText"></p>
                    </div>
                    <button type="button" x-on:click="reset()" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-bold dark:border-slate-700">
                        <x-icon name="scan" size="16" />
                        {{ __('messages.nfc_demo_button') }}
                    </button>
                </div>
            </div>

            <div x-show="!result" x-cloak class="mt-6 grid grid-cols-3 gap-3">
                <button type="button" x-on:click="simulate('allowed')" class="flex flex-col items-center gap-2 rounded-xl border-2 border-emerald-300 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 transition hover:bg-emerald-100 active:scale-95 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200 dark:hover:bg-emerald-950/60">
                    <span class="grid size-10 place-items-center rounded-full bg-emerald-400 text-white"><x-icon name="check" size="22" /></span>
                    <span>{{ __('messages.nfc_allowed') }}</span>
                </button>
                <button type="button" x-on:click="simulate('warning')" class="flex flex-col items-center gap-2 rounded-xl border-2 border-amber-300 bg-amber-50 p-4 text-sm font-bold text-amber-800 transition hover:bg-amber-100 active:scale-95 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-200 dark:hover:bg-amber-950/60">
                    <span class="grid size-10 place-items-center rounded-full bg-amber-400 text-white"><x-icon name="alert" size="22" /></span>
                    <span>{{ __('messages.nfc_warning') }}</span>
                </button>
                <button type="button" x-on:click="simulate('denied')" class="flex flex-col items-center gap-2 rounded-xl border-2 border-rose-300 bg-rose-50 p-4 text-sm font-bold text-rose-800 transition hover:bg-rose-100 active:scale-95 dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-200 dark:hover:bg-rose-950/60">
                    <span class="grid size-10 place-items-center rounded-full bg-rose-400 text-white"><x-icon name="x" size="22" /></span>
                    <span>{{ __('messages.nfc_denied') }}</span>
                </button>
            </div>

            <p class="mt-6 text-center text-xs text-slate-400 dark:text-slate-500">
                {{ __('messages.nfc_demo_hint') }}
            </p>
        </div>
    </div>

    <script>
        function nfcReader() {
            return {
                scanning: false,
                result: null,
                titleText: '{{ __('messages.nfc_reader_title') }}',
                bodyText: '{{ __('messages.nfc_reader_body') }}',
                detailText: '',
                statusClass: 'bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300',
                init() {
                    this.titleText = '{{ __('messages.nfc_reader_title') }}';
                    this.bodyText = '{{ __('messages.nfc_reader_body') }}';
                },
                simulate(type) {
                    if (this.scanning) return;
                    this.scanning = true;
                    this.result = null;
                    this.statusClass = 'bg-amber-100 text-amber-700 motion-safe:animate-pulse dark:bg-amber-950/50 dark:text-amber-300';
                    this.titleText = '{{ __('messages.scanning') }}...';

                    setTimeout(() => {
                        this.scanning = false;
                        this.result = type;

                        if (type === 'allowed') {
                            this.statusClass = 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30';
                            this.titleText = '{{ __('messages.nfc_allowed') }}';
                            this.bodyText = '{{ __('messages.nfc_allowed_desc') }}';
                            this.detailText = '{{ __('messages.nfc_allowed_detail') }}';
                        } else if (type === 'warning') {
                            this.statusClass = 'bg-amber-500 text-white shadow-lg shadow-amber-500/30';
                            this.titleText = '{{ __('messages.nfc_warning') }}';
                            this.bodyText = '{{ __('messages.nfc_warning_desc') }}';
                            this.detailText = '{{ __('messages.nfc_warning_detail') }}';
                        } else {
                            this.statusClass = 'bg-rose-500 text-white shadow-lg shadow-rose-500/30';
                            this.titleText = '{{ __('messages.nfc_denied') }}';
                            this.bodyText = '{{ __('messages.nfc_denied_desc') }}';
                            this.detailText = '{{ __('messages.nfc_denied_detail') }}';
                        }
                    }, 1800);
                },
                reset() {
                    this.scanning = false;
                    this.result = null;
                    this.statusClass = 'bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300';
                    this.titleText = '{{ __('messages.nfc_reader_title') }}';
                    this.bodyText = '{{ __('messages.nfc_reader_body') }}';
                    this.detailText = '';
                }
            };
        }
    </script>
</x-app-layout>
