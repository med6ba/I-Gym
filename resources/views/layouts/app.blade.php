<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-user-theme="{{ auth()->user()->theme ?? 'light' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'I-Gym') }}</title>
        <meta name="theme-color" content="#F59E0B">
        <meta name="description" content="I-Gym smart fitness SaaS platform">
        <link rel="manifest" href="/manifest.json">
        <link rel="icon" href="/icons/icon.svg" type="image/svg+xml">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <script>
            const theme = localStorage.getItem('igym-theme') || document.documentElement.dataset.userTheme || 'light';
            const resolved = theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : theme;
            document.documentElement.classList.toggle('dark', resolved === 'dark');
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans">
        <div class="min-h-screen bg-slate-50 dark:bg-slate-950">
            <div class="flex min-h-screen">
                @auth
                    <x-sidebar />
                @endauth

                <div class="flex min-w-0 flex-1 flex-col">
                    @auth
                        <x-topbar />
                    @endauth

                    @isset($header)
                        <header class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

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
