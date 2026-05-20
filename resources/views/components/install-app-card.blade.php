<section
    x-data="{
        installable: document.body.dataset.pwaInstallable === '1',
        iosInstallable: document.body.dataset.iosInstallable === '1',
        standalone: window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true
    }"
    x-init="
        const observer = new MutationObserver(() => {
            installable = document.body.dataset.pwaInstallable === '1';
            iosInstallable = document.body.dataset.iosInstallable === '1';
        });
        observer.observe(document.body, { attributes: true, attributeFilter: ['data-pwa-installable', 'data-ios-installable'] });
        window.addEventListener('appinstalled', () => standalone = true);
    "
    class="overflow-hidden rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-900/50 dark:bg-amber-950/20"
>
    <div class="space-y-3 p-3.5">
        <div class="flex gap-3">
            <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-white text-amber-600 shadow-sm dark:bg-slate-900 dark:text-amber-300">
                <x-icon name="download" size="18" />
            </span>
            <div class="min-w-0">
                <h3 class="text-sm font-black text-slate-950 dark:text-white">{{ __('messages.install_app_title') }}</h3>
                <p class="mt-1 text-xs leading-5 text-slate-600 dark:text-slate-400">{{ __('messages.install_app_body') }}</p>
            </div>
        </div>

        <p x-show="iosInstallable && !standalone" x-cloak class="rounded-lg bg-white/70 px-3 py-2 text-xs font-bold leading-5 text-amber-700 dark:bg-slate-900/70 dark:text-amber-300">{{ __('messages.ios_install_instructions') }}</p>
        <p x-show="standalone" x-cloak class="rounded-lg bg-emerald-50 px-3 py-2 text-xs font-bold leading-5 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300">{{ __('messages.app_installed') }}</p>

        <button
            type="button"
            x-show="installable && !standalone"
            x-cloak
            onclick="installPwa()"
            class="igym-focus inline-flex w-full items-center justify-center gap-2 rounded-lg bg-amber-500 px-3 py-2.5 text-sm font-black text-slate-950 transition hover:bg-amber-400"
        >
            <x-icon name="download" size="17" />
            {{ __('messages.install_app') }}
        </button>
    </div>
</section>
