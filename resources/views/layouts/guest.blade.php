<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-authenticated="{{ auth()->check() ? '1' : '0' }}" data-user-theme="{{ auth()->user()->theme ?? 'light' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#F59E0B">
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
    <body class="font-sans">
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
