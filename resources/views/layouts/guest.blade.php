<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-authenticated="{{ auth()->check() ? '1' : '0' }}" data-user-theme="{{ auth()->user()->theme ?? 'light' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#F59E0B">
        <link rel="manifest" href="/manifest.json">
        <link rel="icon" href="/icons/igym-logo.svg" type="image/svg+xml">

        <title>{{ config('app.name', 'I-Gym') }}</title>

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
    <body class="font-sans">
        <div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
            <div class="mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-6">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('landing') }}" class="igym-focus rounded-lg">
                        <x-application-logo />
                    </a>
                    <div class="flex items-center gap-3">
                        <x-language-switcher />
                        <x-theme-toggle />
                    </div>
                </div>

                <div class="grid flex-1 place-items-center py-10">
                    <div class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
