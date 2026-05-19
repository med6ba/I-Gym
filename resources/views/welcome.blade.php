<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-user-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#F59E0B">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/icons/icon.svg" type="image/svg+xml">
    <title>I-Gym - Smart Fitness Management for the Next Generation</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script>
        const theme = localStorage.getItem('igym-theme') || 'light';
        const resolved = theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : theme;
        document.documentElement.classList.toggle('dark', resolved === 'dark');
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
    <div class="min-h-screen bg-white text-slate-950 dark:bg-slate-950 dark:text-white">
        <header class="absolute inset-x-0 top-0 z-20">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
                <x-application-logo />
                <div class="flex items-center gap-3">
                    <x-language-switcher />
                    <x-theme-toggle />
                    @auth
                        <a href="{{ role_home_route() }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 transition hover:bg-amber-400">{{ __('messages.dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 transition hover:bg-amber-400">{{ __('messages.login') }}</a>
                    @endauth
                </div>
            </div>
        </header>

        <section class="relative min-h-[92vh] overflow-hidden bg-cover bg-center" style="background-image: linear-gradient(90deg, rgba(15,23,42,.92), rgba(15,23,42,.68), rgba(15,23,42,.18)), url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1800&q=80');">
            <div class="mx-auto flex min-h-[92vh] max-w-7xl items-center px-4 pb-16 pt-28 sm:px-6 lg:px-8">
                <div class="max-w-3xl text-white">
                    <p class="text-sm font-black uppercase tracking-normal text-amber-300">I-Gym</p>
                    <h1 class="mt-4 text-4xl font-black tracking-normal sm:text-6xl">Smart Fitness Management for the Next Generation.</h1>
                    <p class="mt-6 max-w-2xl text-lg text-slate-200">Manage access, bookings, coaching, subscriptions, retention, and SaaS dashboards in one intelligent platform built for modern gyms.</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="rounded-lg bg-amber-500 px-5 py-3 text-sm font-black text-slate-950 transition hover:bg-amber-400">{{ __('messages.login') }}</a>
                        <a href="#features" class="rounded-lg border border-white/30 px-5 py-3 text-sm font-black text-white transition hover:bg-white/10">Explore Platform</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="border-b border-slate-200 bg-slate-50 py-16 dark:border-slate-800 dark:bg-slate-900">
            <div class="mx-auto grid max-w-7xl gap-4 px-4 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 lg:px-8">
                @foreach([
                    ['QR access', 'Simulated member access with scannable QR codes and coach check-in.'],
                    ['Smart booking', 'Capacity rules, no duplicate reservations, and high occupancy alerts.'],
                    ['Coach tools', 'Assigned classes, attendance, training plans, and progress tracking.'],
                    ['SaaS layer', 'Super Admin manages customer gyms, plans, status, and global analytics.'],
                ] as $feature)
                    <div class="rounded-xl border border-slate-200 bg-white p-5 transition hover:-translate-y-1 hover:border-amber-300 dark:border-slate-800 dark:bg-slate-950">
                        <h3 class="text-lg font-black">{{ $feature[0] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $feature[1] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="py-16">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:px-8">
                <div>
                    <h2 class="text-3xl font-black tracking-normal">Built for the whole gym workflow.</h2>
                    <p class="mt-4 text-slate-600 dark:text-slate-400">Super admins, gym admins, coaches, and members each get a focused dashboard with the data and actions they need.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach(['SaaS for gyms', 'Smart access', 'Booking management', 'Member experience'] as $label)
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                            <p class="font-bold">{{ $label }}</p>
                            <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-slate-800"><div class="h-2 w-2/3 rounded-full bg-amber-500"></div></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <footer class="border-t border-slate-200 py-8 text-center text-sm text-slate-500 dark:border-slate-800 dark:text-slate-400">
            I-Gym · Smart Fitness Management for the Next Generation.
        </footer>
    </div>
</body>
</html>
