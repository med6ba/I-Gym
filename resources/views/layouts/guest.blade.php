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
        <div class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-950 dark:bg-slate-950 dark:text-white">
            <div class="absolute inset-0 bg-cover bg-center opacity-10 dark:hidden" style="background-image: linear-gradient(90deg, rgba(255,255,255,.92), rgba(255,247,237,.72)), url('https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=1800&q=80');"></div>
            <div class="absolute inset-0 hidden bg-cover bg-center opacity-20 dark:block" style="background-image: linear-gradient(90deg, rgba(2,6,23,.98), rgba(15,23,42,.86)), url('https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=1800&q=80');"></div>
            <div class="absolute inset-0 hidden bg-slate-950/70 dark:block"></div>
            <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-6">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('landing') }}" class="igym-focus rounded-lg">
                        <span class="dark:hidden"><x-application-logo /></span>
                        <span class="hidden dark:block"><x-application-logo tone="inverse" /></span>
                    </a>
                    <div class="flex items-center gap-3">
                        <x-language-switcher />
                        <x-theme-toggle />
                    </div>
                </div>

                <div class="grid flex-1 place-items-center py-10">
                    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white/95 p-6 text-slate-950 shadow-2xl shadow-slate-950/10 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/95 dark:text-white dark:shadow-black/30">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
