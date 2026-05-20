<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-authenticated="{{ auth()->check() ? '1' : '0' }}" data-user-theme="{{ auth()->user()->theme ?? 'light' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ ($title ?? igym_current_page_title()) ?: __('messages.dashboard') }} — {{ config('app.name') }}</title>
        <meta name="theme-color" content="#F59E0B">
        <meta name="description" content="I-Gym smart fitness app">
        <link rel="manifest" href="/manifest.json">
        <link rel="icon" href="/icons/igym-logo.svg" type="image/svg+xml">

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
    <body class="font-sans" data-notification-count="{{ auth()->check() ? igym_unread_notification_count() : 0 }}">
        <div x-data class="min-h-screen bg-slate-50 dark:bg-slate-950">
            <div class="flex min-h-screen"
                  x-data="{ sidebarOpen: false, windowWidth: window.innerWidth }"
                  x-init="window.addEventListener('resize', () => windowWidth = window.innerWidth)"
                  x-on:open-sidebar="sidebarOpen = true; document.body.classList.add('overflow-hidden')"
                  x-on:close-sidebar="sidebarOpen = false; document.body.classList.remove('overflow-hidden')"
                  x-on:keydown.escape="sidebarOpen = false; document.body.classList.remove('overflow-hidden')">
                @auth
                    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden" x-on:click="sidebarOpen = false; document.body.classList.remove('overflow-hidden')" x-cloak></div>
                    <x-sidebar />
                @endauth

                <div class="flex min-w-0 flex-1 flex-col lg:ms-72">
                    <div class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
                        <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-8">
                            <div class="flex min-w-0 shrink items-center gap-3">
                                <button type="button" x-data x-on:click="$dispatch('open-sidebar')" class="igym-focus grid size-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30 lg:hidden" title="{{ __('messages.menu') }}">
                                    <x-icon name="menu" size="18" />
                                </button>
                                @isset($header)
                                    <div class="min-w-0">
                                        {{ $header }}
                                    </div>
                                @endisset
                            </div>

                        </div>
                    </div>

                    <main class="flex-1 pb-24 lg:pb-8">
                        {{ $slot }}
                    </main>

                    @auth
                        <x-mobile-nav />
                    @endauth
                </div>
            </div>
        </div>
    </body>
</html>
