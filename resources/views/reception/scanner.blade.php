<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.reception_scanner') }}</h2></x-slot>

    <div class="mx-auto max-w-5xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <section class="grid gap-6 lg:grid-cols-[1fr_.8fr]">
            <div class="igym-card p-5 sm:p-6">
                <div class="flex items-start gap-4">
                    <span class="grid size-12 place-items-center rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300">
                        <x-icon name="scan" size="24" />
                    </span>
                    <div>
                        <h2 class="text-xl font-black text-slate-950 dark:text-white">{{ __('messages.fake_scanner_title') }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ __('messages.fake_scanner_body') }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('reception.scanner.scan') }}" class="mt-6 space-y-4">
                    @csrf
                    <label class="block">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ __('messages.qr_payload') }}</span>
                        <textarea name="payload" rows="5" required autofocus placeholder="IGYM|member:1|gym:1|issued:..." class="mt-2 w-full rounded-xl border-slate-200 font-mono text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 focus:border-amber-500 focus:ring-amber-500">{{ old('payload') }}</textarea>
                    </label>
                    <x-button class="w-full gap-2 sm:w-auto">
                        <x-icon name="scan" size="17" />
                        {{ __('messages.scan_qr_access') }}
                    </x-button>
                </form>
            </div>

            <div class="igym-card p-5 sm:p-6">
                <h3 class="font-black text-slate-950 dark:text-white">{{ __('messages.recent_scans') }}</h3>
                <div class="mt-4 space-y-3">
                    @forelse($recentScans as $scan)
                        <div class="rounded-xl border border-slate-200 p-3 dark:border-slate-800">
                            <p class="text-sm font-bold text-slate-950 dark:text-white">{{ $scan->description }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $scan->created_at->format('M d, H:i') }} · {{ $scan->actor?->name ?? __('messages.system') }}</p>
                        </div>
                    @empty
                        <x-empty-state :message="__('messages.no_scans_yet')" />
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
