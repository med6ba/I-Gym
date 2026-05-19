<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-authenticated="{{ auth()->check() ? '1' : '0' }}" data-user-theme="{{ auth()->user()->theme ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#F59E0B">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/icons/igym-logo.svg" type="image/svg+xml">
    <title>I-Gym - {{ __('messages.smart_fitness_management') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
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
    <div class="min-h-screen bg-white text-slate-950 dark:bg-slate-950 dark:text-white" x-data="{ product: false, solutions: false, mobile: false, scrolled: window.scrollY > 12 }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 12, { passive: true })">
        <header class="fixed inset-x-0 top-0 z-40 border-b backdrop-blur-xl transition-all duration-300" :class="scrolled ? 'border-slate-200 bg-white/95 shadow-lg shadow-slate-950/5 dark:border-slate-800 dark:bg-slate-950/95' : 'border-white/10 bg-slate-950/90'">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('landing') }}" class="igym-focus rounded-xl" :class="scrolled ? 'text-slate-950 dark:text-white' : 'text-white'">
                    <span x-show="!scrolled">
                    <x-application-logo tone="inverse" />
                    </span>
                    <span x-cloak x-show="scrolled">
                        <x-application-logo />
                    </span>
                </a>

                <nav class="hidden items-center gap-2 lg:flex">
                    <div class="relative">
                        <button type="button" x-on:click="product = ! product; solutions = false" class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-bold transition" :class="scrolled ? 'text-slate-700 hover:bg-amber-50 hover:text-amber-800 dark:text-slate-200 dark:hover:bg-slate-900' : 'text-slate-200 hover:bg-white/10 hover:text-white'">
                            {{ __('messages.product') }}
                            <x-icon name="chevron-down" size="15" />
                        </button>
                        <div x-cloak x-show="product" x-transition x-on:click.outside="product = false" class="absolute start-0 mt-3 w-80 rounded-2xl border border-white/10 bg-slate-950 p-2 shadow-2xl shadow-slate-950/40">
                            @foreach([
                                ['icon' => 'dashboard', 'title' => __('messages.role_dashboards'), 'text' => __('messages.role_dashboards_text')],
                                ['icon' => 'qr', 'title' => __('messages.qr_access'), 'text' => __('messages.qr_access_text')],
                                ['icon' => 'sparkles', 'title' => __('messages.smart_insights'), 'text' => __('messages.smart_insights_text')],
                            ] as $item)
                                <a href="#platform" class="flex gap-3 rounded-xl p-3 text-slate-200 transition hover:bg-white/10">
                                    <span class="grid size-10 place-items-center rounded-xl bg-amber-500 text-slate-950"><x-icon name="{{ $item['icon'] }}" size="19" /></span>
                                    <span><span class="block font-black text-white">{{ $item['title'] }}</span><span class="mt-1 block text-sm text-slate-400">{{ $item['text'] }}</span></span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative">
                        <button type="button" x-on:click="solutions = ! solutions; product = false" class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-bold transition" :class="scrolled ? 'text-slate-700 hover:bg-amber-50 hover:text-amber-800 dark:text-slate-200 dark:hover:bg-slate-900' : 'text-slate-200 hover:bg-white/10 hover:text-white'">
                            {{ __('messages.solutions') }}
                            <x-icon name="chevron-down" size="15" />
                        </button>
                        <div x-cloak x-show="solutions" x-transition x-on:click.outside="solutions = false" class="absolute start-0 mt-3 w-80 rounded-2xl border border-white/10 bg-slate-950 p-2 shadow-2xl shadow-slate-950/40">
                            @foreach([
                                ['icon' => 'building', 'title' => __('messages.saas_owners'), 'text' => __('messages.saas_owners_text')],
                                ['icon' => 'coach', 'title' => __('messages.gym_teams'), 'text' => __('messages.gym_teams_text')],
                                ['icon' => 'users', 'title' => __('messages.members'), 'text' => __('messages.members_solution_text')],
                            ] as $item)
                                <a href="#workflows" class="flex gap-3 rounded-xl p-3 text-slate-200 transition hover:bg-white/10">
                                    <span class="grid size-10 place-items-center rounded-xl bg-white/10 text-amber-300"><x-icon name="{{ $item['icon'] }}" size="19" /></span>
                                    <span><span class="block font-black text-white">{{ $item['title'] }}</span><span class="mt-1 block text-sm text-slate-400">{{ $item['text'] }}</span></span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="#security" class="rounded-xl px-3 py-2 text-sm font-bold transition" :class="scrolled ? 'text-slate-700 hover:bg-amber-50 hover:text-amber-800 dark:text-slate-200 dark:hover:bg-slate-900' : 'text-slate-200 hover:bg-white/10 hover:text-white'">{{ __('messages.security') }}</a>
                    <a href="#demo" class="rounded-xl px-3 py-2 text-sm font-bold transition" :class="scrolled ? 'text-slate-700 hover:bg-amber-50 hover:text-amber-800 dark:text-slate-200 dark:hover:bg-slate-900' : 'text-slate-200 hover:bg-white/10 hover:text-white'">{{ __('messages.demo') }}</a>
                </nav>

                <div class="hidden items-center gap-2 lg:flex">
                    <x-language-switcher />
                    <x-theme-toggle />
                    @auth
                        <a href="{{ role_home_route() }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 transition hover:bg-amber-400">
                            <x-icon name="dashboard" size="17" />
                            {{ __('messages.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 transition hover:bg-amber-400">
                            <x-icon name="lock" size="17" />
                            {{ __('messages.login') }}
                        </a>
                    @endauth
                </div>

                <button type="button" x-on:click="mobile = ! mobile" class="igym-focus grid size-11 place-items-center rounded-xl border transition lg:hidden" :class="scrolled ? 'border-slate-200 text-slate-800 dark:border-slate-700 dark:text-white' : 'border-white/15 text-white'">
                    <x-icon name="menu" size="22" />
                </button>
            </div>

            <div x-cloak x-show="mobile" x-transition class="border-t border-white/10 bg-slate-950 px-4 py-4 lg:hidden">
                <div class="space-y-2">
                    @foreach([
                        ['label' => __('messages.product'), 'href' => '#platform', 'icon' => 'dashboard'],
                        ['label' => __('messages.solutions'), 'href' => '#workflows', 'icon' => 'building'],
                        ['label' => __('messages.security'), 'href' => '#security', 'icon' => 'shield'],
                        ['label' => __('messages.demo'), 'href' => '#demo', 'icon' => 'sparkles'],
                    ] as $item)
                        <a href="{{ $item['href'] }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold text-slate-200 hover:bg-white/10">
                            <x-icon name="{{ $item['icon'] }}" size="18" class="text-amber-400" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <x-language-switcher />
                    <x-theme-toggle />
                </div>
                <a href="{{ auth()->check() ? role_home_route() : route('login') }}" class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-3 text-sm font-black text-slate-950">
                    <x-icon name="{{ auth()->check() ? 'dashboard' : 'lock' }}" size="18" />
                    {{ auth()->check() ? __('messages.dashboard') : __('messages.login') }}
                </a>
            </div>
        </header>

        <main>
            <section class="relative min-h-[88vh] overflow-hidden bg-cover bg-center" style="background-image: linear-gradient(90deg, rgba(2,6,23,.95), rgba(15,23,42,.72), rgba(15,23,42,.34)), url('https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=2200&q=85');">
                <div class="mx-auto flex min-h-[88vh] max-w-7xl items-center px-4 pb-20 pt-32 sm:px-6 lg:px-8">
                    <div class="max-w-4xl text-white">
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-300/30 bg-amber-300/10 px-3 py-1.5 text-sm font-black text-amber-200">
                            <x-icon name="sparkles" size="16" />
                            {{ __('messages.next_generation_saas') }}
                        </div>
                        <h1 class="mt-6 max-w-4xl text-4xl font-black leading-tight tracking-normal sm:text-6xl lg:text-7xl">{{ __('messages.landing_title') }}</h1>
                        <p class="mt-6 max-w-2xl text-base leading-8 text-slate-200 sm:text-lg">{{ __('messages.landing_subtitle') }}</p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-3 text-sm font-black text-slate-950 transition hover:bg-amber-400">
                                <x-icon name="lock" size="18" />
                                {{ __('messages.start_demo') }}
                            </a>
                            <a href="#demo" class="inline-flex items-center gap-2 rounded-xl border border-white/25 px-5 py-3 text-sm font-black text-white transition hover:bg-white/10">
                                {{ __('messages.watch_flow') }}
                                <x-icon name="arrow-right" size="18" class="rtl:rotate-180" />
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="-mt-16 relative z-10 px-4 sm:px-6 lg:px-8">
                <div class="mx-auto grid max-w-7xl gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900 md:grid-cols-4">
                    @foreach([
                        ['icon' => 'building', 'value' => '3+', 'label' => __('messages.customer_gyms')],
                        ['icon' => 'users', 'value' => '4', 'label' => __('messages.secure_roles')],
                        ['icon' => 'qr', 'value' => 'QR', 'label' => __('messages.access_simulation')],
                        ['icon' => 'sparkles', 'value' => 'AI', 'label' => __('messages.recommendations')],
                    ] as $stat)
                        <div class="flex items-center gap-3 rounded-xl border border-slate-100 p-4 dark:border-slate-800">
                            <span class="grid size-11 place-items-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300"><x-icon name="{{ $stat['icon'] }}" size="20" /></span>
                            <div><p class="text-2xl font-black">{{ $stat['value'] }}</p><p class="text-sm font-semibold text-slate-500">{{ $stat['label'] }}</p></div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section id="platform" class="scroll-mt-24 py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                        <p class="text-sm font-black uppercase text-amber-600">{{ __('messages.platform') }}</p>
                        <h2 class="mt-3 text-3xl font-black tracking-normal sm:text-4xl">{{ __('messages.platform_title') }}</h2>
                    </div>
                    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach([
                            ['icon' => 'dashboard', 'title' => __('messages.intelligent_dashboards'), 'text' => __('messages.intelligent_dashboards_text')],
                            ['icon' => 'calendar', 'title' => __('messages.smart_bookings'), 'text' => __('messages.smart_bookings_text')],
                            ['icon' => 'qr', 'title' => __('messages.qr_access'), 'text' => __('messages.qr_access_feature_text')],
                            ['icon' => 'activity', 'title' => __('messages.progress_tracking'), 'text' => __('messages.progress_tracking_text')],
                        ] as $feature)
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-1 hover:border-amber-300 dark:border-slate-800 dark:bg-slate-900">
                                <span class="grid size-11 place-items-center rounded-xl bg-slate-100 text-amber-600 dark:bg-slate-800 dark:text-amber-300"><x-icon name="{{ $feature['icon'] }}" size="21" /></span>
                                <h3 class="mt-5 text-lg font-black">{{ $feature['title'] }}</h3>
                                <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $feature['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="workflows" class="scroll-mt-24 border-y border-slate-200 bg-slate-50 py-20 dark:border-slate-800 dark:bg-slate-900">
                <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:px-8">
                    <div>
                        <p class="text-sm font-black uppercase text-amber-600">{{ __('messages.workflow') }}</p>
                        <h2 class="mt-3 text-3xl font-black tracking-normal sm:text-4xl">{{ __('messages.workflow_title') }}</h2>
                        <p class="mt-4 leading-7 text-slate-600 dark:text-slate-400">{{ __('messages.workflow_text') }}</p>
                    </div>
                    <div class="grid gap-3">
                        @foreach([
                            ['icon' => 'shield', 'title' => __('messages.super_admin'), 'text' => __('messages.super_admin_text')],
                            ['icon' => 'building', 'title' => __('messages.gym_admin'), 'text' => __('messages.gym_admin_text')],
                            ['icon' => 'coach', 'title' => __('messages.coach'), 'text' => __('messages.coach_text')],
                            ['icon' => 'user', 'title' => __('messages.member'), 'text' => __('messages.member_text')],
                        ] as $role)
                            <div class="flex gap-4 rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950">
                                <span class="grid size-12 place-items-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300"><x-icon name="{{ $role['icon'] }}" size="22" /></span>
                                <div><h3 class="font-black">{{ $role['title'] }}</h3><p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $role['text'] }}</p></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="security" class="scroll-mt-24 py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid gap-8 lg:grid-cols-[1fr_1fr]">
                        <div>
                            <p class="text-sm font-black uppercase text-amber-600">{{ __('messages.security') }}</p>
                            <h2 class="mt-3 text-3xl font-black tracking-normal sm:text-4xl">{{ __('messages.security_title') }}</h2>
                            <p class="mt-4 leading-7 text-slate-600 dark:text-slate-400">{{ __('messages.security_text') }}</p>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach([
                                ['icon' => 'lock', 'title' => __('messages.auth_first_routes')],
                                ['icon' => 'shield', 'title' => __('messages.role_middleware')],
                                ['icon' => 'building', 'title' => __('messages.gym_status_gate')],
                                ['icon' => 'target', 'title' => __('messages.tenant_bindings')],
                            ] as $security)
                                <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                                    <x-icon name="{{ $security['icon'] }}" size="24" class="text-amber-500" />
                                    <p class="mt-4 font-black">{{ $security['title'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section id="demo" class="scroll-mt-24 bg-slate-950 py-20 text-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap items-end justify-between gap-5">
                        <div>
                            <p class="text-sm font-black uppercase text-amber-300">{{ __('messages.demo_ready') }}</p>
                            <h2 class="mt-3 text-3xl font-black tracking-normal sm:text-4xl">{{ __('messages.demo_ready_title') }}</h2>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-3 text-sm font-black text-slate-950 transition hover:bg-amber-400">
                            {{ __('messages.open_login') }}
                            <x-icon name="arrow-right" size="18" class="rtl:rotate-180" />
                        </a>
                    </div>
                    <div class="mt-8 grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                        @foreach([
                            [__('messages.super_admin'), __('messages.demo_super_text')],
                            [__('messages.gym_admin'), __('messages.demo_admin_text')],
                            [__('messages.member'), __('messages.demo_member_text')],
                            [__('messages.coach'), __('messages.demo_coach_text')],
                        ] as $step)
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                <p class="font-black text-amber-200">{{ $step[0] }}</p>
                                <p class="mt-3 text-sm leading-6 text-slate-300">{{ $step[1] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-200 py-8 dark:border-slate-800">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 text-sm text-slate-500 sm:px-6 lg:px-8">
                <x-application-logo />
                <p>{{ __('messages.next_generation_saas') }}</p>
            </div>
        </footer>
    </div>
</body>
</html>
