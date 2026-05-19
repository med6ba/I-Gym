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
        <div class="min-h-screen bg-slate-950 text-white">
            <div class="absolute inset-0 bg-cover bg-center opacity-50" style="background-image: linear-gradient(90deg, rgba(2,6,23,.98), rgba(15,23,42,.78)), url('https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=1800&q=80');"></div>
            <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-6">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('landing') }}" class="igym-focus rounded-lg">
                        <x-application-logo tone="inverse" />
                    </a>
                    <div class="flex items-center gap-3">
                        <x-language-switcher />
                        <x-theme-toggle />
                    </div>
                </div>

                <div class="grid flex-1 place-items-center py-10">
                    <div class="w-full max-w-md rounded-2xl border border-white/10 bg-slate-950/78 p-6 text-white shadow-2xl shadow-slate-950/40 backdrop-blur-xl">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
