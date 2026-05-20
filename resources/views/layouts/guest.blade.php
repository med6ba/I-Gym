<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-authenticated="{{ auth()->check() ? '1' : '0' }}" data-user-theme="{{ auth()->user()->theme ?? 'light' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#F59E0B">
        <meta name="description" content="I-Gym smart fitness app">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="I-Gym">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="manifest" href="/manifest.json">
        <link rel="icon" href="/icons/igym-logo.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/icons/icon-180.png">

        <title>{{ $title ?? __('messages.welcome') }} — {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <script>
            const theme = document.documentElement.dataset.authenticated === '1'
                ? (document.documentElement.dataset.userTheme || 'light')
                : (localStorage.getItem('igym-theme') || 'light');
            const resolved = theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : theme;
            document.documentElement.classList.toggle('dark', resolved === 'dark');
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="font-sans"
        x-data="{
            installable: document.body.dataset.pwaInstallable === '1',
            iosInstallable: document.body.dataset.iosInstallable === '1',
            isOnline: typeof navigator === 'undefined' ? true : navigator.onLine
        }"
        x-init="
            const observer = new MutationObserver(() => {
                installable = document.body.dataset.pwaInstallable === '1';
                iosInstallable = document.body.dataset.iosInstallable === '1';
                isOnline = document.body.dataset.isOnline === '1';
            });
            observer.observe(document.body, { attributes: true, attributeFilter: ['data-pwa-installable', 'data-ios-installable', 'data-is-online'] });
        "
    >
        <div x-show="!isOnline" class="fixed inset-x-0 top-0 z-50 bg-red-600 px-4 py-2 text-center text-xs font-bold text-white shadow-lg" x-cloak>
            {{ __('messages.you_are_offline') }}
        </div>
        <button x-show="installable" x-cloak type="button" onclick="installPwa()" class="fixed bottom-5 end-5 z-50 inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-3 text-sm font-black text-slate-950 shadow-xl shadow-amber-900/20 transition hover:bg-amber-400">
            <x-icon name="download" size="17" />
            {{ __('messages.install_app') }}
        </button>
        <div x-show="iosInstallable" x-cloak class="fixed inset-x-4 bottom-5 z-50 rounded-xl bg-white/95 p-3 text-sm font-bold text-slate-700 shadow-xl dark:bg-slate-900/95 dark:text-slate-200">
            {{ __('messages.ios_install_instructions') }}
        </div>
        <div class="relative min-h-screen overflow-hidden bg-slate-950 text-white">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: linear-gradient(90deg, rgba(2,6,23,.88), rgba(15,23,42,.68), rgba(15,23,42,.50)), url('https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=1800&q=80');"></div>
            <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-3 py-4 sm:px-4 sm:py-6">
                <div class="flex flex-wrap items-center justify-between gap-3 sm:gap-4">
                    <a href="{{ route('landing') }}" class="igym-focus rounded-lg">
                        <x-application-logo tone="inverse" />
                    </a>
                    <div class="flex items-center gap-3">
                        <x-language-switcher />
                        <x-theme-toggle />
                    </div>
                </div>

                <div class="grid flex-1 place-items-center py-6 sm:py-10">
                    <div class="igym-auth-panel w-full max-w-sm sm:max-w-md">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
