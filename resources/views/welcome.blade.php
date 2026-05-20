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
    <title>{{ __('messages.welcome') }} — {{ config('app.name') }}</title>
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
        <header class="fixed inset-x-0 top-0 z-40 border-b backdrop-blur-xl transition-all duration-300" :class="scrolled ? 'border-slate-200 bg-white/95 shadow-lg shadow-slate-950/5 dark:border-slate-800 dark:bg-slate-950/95' : 'border-white/10 bg-slate-950/80 shadow-none'">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:gap-4 sm:px-6 sm:py-4 lg:px-8">
                <a href="{{ route('landing') }}" class="igym-focus rounded-xl">
                    <span x-show="!scrolled">
                        <x-application-logo tone="inverse" />
                    </span>
                    <span x-cloak x-show="scrolled">
                        <x-application-logo />
                    </span>
                </a>

                <nav class="hidden items-center gap-2 lg:flex">
                    <div class="relative" x-on:mouseenter="product = true; solutions = false" x-on:mouseleave="product = false" x-on:focusin="product = true; solutions = false" x-on:focusout="product = false">
                        <button type="button" class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-bold transition" :class="scrolled ? 'text-slate-700 hover:bg-amber-50 hover:text-amber-800 dark:text-slate-200 dark:hover:bg-slate-900' : 'text-white hover:bg-white/10'">
                            {{ __('messages.product') }}
                            <x-icon name="chevron-down" size="15" />
                        </button>
                        <div x-cloak x-show="product" x-transition class="absolute start-0 mt-3 w-80 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl shadow-slate-950/10 dark:border-white/10 dark:bg-slate-950 dark:shadow-slate-950/40">
                            @foreach([
                                ['icon' => 'dashboard', 'title' => __('messages.role_dashboards'), 'text' => __('messages.role_dashboards_text')],
                                ['icon' => 'nfc', 'title' => __('messages.nfc_access'), 'text' => __('messages.nfc_access_text')],
                                ['icon' => 'sparkles', 'title' => __('messages.igyma_assistant'), 'text' => __('messages.igyma_members_only_assistant')],
                            ] as $item)
                                <div class="group flex gap-3 rounded-xl p-3 text-slate-700 transition hover:bg-amber-50 dark:text-slate-200 dark:hover:bg-white/10">
                                    <span class="igym-menu-icon"><x-icon name="{{ $item['icon'] }}" size="18" /></span>
                                    <span><span class="block font-black text-slate-950 dark:text-white">{{ $item['title'] }}</span><span class="mt-1 block text-sm text-slate-500 dark:text-slate-400">{{ $item['text'] }}</span></span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative" x-on:mouseenter="solutions = true; product = false" x-on:mouseleave="solutions = false" x-on:focusin="solutions = true; product = false" x-on:focusout="solutions = false">
                        <button type="button" class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-bold transition" :class="scrolled ? 'text-slate-700 hover:bg-amber-50 hover:text-amber-800 dark:text-slate-200 dark:hover:bg-slate-900' : 'text-white hover:bg-white/10'">
                            {{ __('messages.solutions') }}
                            <x-icon name="chevron-down" size="15" />
                        </button>
                        <div x-cloak x-show="solutions" x-transition class="absolute start-0 mt-3 w-80 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl shadow-slate-950/10 dark:border-white/10 dark:bg-slate-950 dark:shadow-slate-950/40">
                            @foreach([
                                ['icon' => 'building', 'title' => __('messages.saas_owners'), 'text' => __('messages.saas_owners_text')],
                                ['icon' => 'coach', 'title' => __('messages.gym_teams'), 'text' => __('messages.gym_teams_text')],
                                ['icon' => 'users', 'title' => __('messages.members'), 'text' => __('messages.members_solution_text')],
                            ] as $item)
                                <div class="group flex gap-3 rounded-xl p-3 text-slate-700 transition hover:bg-amber-50 dark:text-slate-200 dark:hover:bg-white/10">
                                    <span class="igym-menu-icon"><x-icon name="{{ $item['icon'] }}" size="18" /></span>
                                    <span><span class="block font-black text-slate-950 dark:text-white">{{ $item['title'] }}</span><span class="mt-1 block text-sm text-slate-500 dark:text-slate-400">{{ $item['text'] }}</span></span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </nav>

<div class="hidden items-center gap-2 lg:flex">
    <x-language-switcher />
    <x-theme-toggle />
    @auth
        <a href="{{ route('profile.edit') }}" class="igym-focus grid size-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30" title="{{ __('messages.profile') }}">
            <x-icon name="user" size="18" />
        </a>
        <a href="{{ route('settings.index') }}" class="igym-focus grid size-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30" title="{{ __('messages.settings') }}">
            <x-icon name="settings" size="18" />
        </a>
        <a href="{{ role_home_route() }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 transition hover:bg-amber-400">
            <x-icon name="dashboard" size="17" />
            {{ __('messages.dashboard') }}
        </a>
    @else
        <a href="{{ route('login') }}" class="igym-focus inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-black text-slate-950 transition hover:bg-amber-400" aria-label="{{ __('messages.login') }}" title="{{ __('messages.login') }}">
            <x-icon name="log-in" size="18" />
            <span>{{ __('messages.login') }}</span>
        </a>
    @endauth
</div>

                <button type="button" x-on:click="mobile = ! mobile" class="igym-focus grid size-11 place-items-center rounded-xl border transition lg:hidden" :class="scrolled ? 'border-slate-200 text-slate-700 dark:border-slate-700 dark:text-white' : 'border-white/15 bg-white/10 text-white'">
                    <x-icon name="menu" size="22" />
                </button>
            </div>

<div x-cloak x-show="mobile" x-transition class="border-t border-white/10 bg-slate-950/95 px-4 py-4 text-white backdrop-blur-xl sm:py-5 lg:hidden">
    <div class="flex items-center justify-center gap-3">
        <x-language-switcher />
        <x-theme-toggle />
    </div>
    @auth
        <a href="{{ role_home_route() }}" class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-3 text-sm font-black text-slate-950 transition hover:bg-amber-400">
            <x-icon name="dashboard" size="18" />
            {{ __('messages.dashboard') }}
        </a>
    @else
        <a href="{{ route('login') }}" class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-3 text-sm font-black text-slate-950 transition hover:bg-amber-400">
            <x-icon name="log-in" size="18" />
            {{ __('messages.login') }}
        </a>
    @endauth
</div>
        </header>

        <main>
            <section class="igym-landing-hero relative min-h-[76vh] overflow-hidden bg-cover bg-center sm:min-h-[88vh]">
                <div class="mx-auto flex min-h-[76vh] max-w-7xl items-center px-4 pb-14 pt-28 sm:min-h-[88vh] sm:px-6 sm:pb-20 sm:pt-32 lg:px-8">
                    <div class="max-w-4xl text-white">
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-300/40 bg-amber-300/15 px-3 py-1.5 text-xs font-black text-amber-100 backdrop-blur-sm sm:text-sm">
                            <x-icon name="sparkles" size="16" />
                            {{ __('messages.next_generation_saas') }}
                        </div>
                        <h1 class="mt-5 max-w-4xl text-3xl font-black leading-tight tracking-normal sm:mt-6 sm:text-5xl lg:text-7xl">{{ __('messages.landing_title') }}</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-200 sm:mt-6 sm:text-lg sm:leading-8">{{ __('messages.landing_subtitle') }}</p>
                        <div class="mt-6 flex flex-wrap gap-3 sm:mt-8">
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-black text-slate-950 transition hover:bg-amber-400 sm:px-5 sm:py-3">
                                <x-icon name="log-in" size="18" />
                                {{ __('messages.start_demo') }}
                            </a>
                            <a href="#demo" class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-black/35 px-4 py-2.5 text-sm font-black text-white transition hover:bg-black/50 sm:px-5 sm:py-3">
                                {{ __('messages.watch_flow') }}
                                <x-icon name="arrow-right" size="18" class="rtl:rotate-180" />
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="-mt-10 relative z-10 px-4 sm:-mt-16 sm:px-6 lg:px-8">
                <div class="mx-auto grid max-w-7xl gap-2.5 rounded-xl border border-slate-200 bg-white p-2.5 shadow-xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900 sm:gap-3 sm:rounded-2xl sm:p-3 md:grid-cols-4">
                    @foreach([
                        ['icon' => 'building', 'value' => '3+', 'label' => __('messages.customer_gyms')],
                        ['icon' => 'users', 'value' => '4', 'label' => __('messages.secure_roles')],
                        ['icon' => 'nfc', 'value' => 'NFC', 'label' => __('messages.access_simulation')],
                        ['icon' => 'credit-card', 'value' => '2', 'label' => __('messages.subscriptions')],
                    ] as $stat)
                        <div class="flex items-center gap-3 rounded-xl border border-slate-100 p-3 dark:border-slate-800 sm:p-4">
                            <span class="grid size-10 place-items-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 sm:size-11 sm:rounded-xl"><x-icon name="{{ $stat['icon'] }}" size="20" /></span>
                            <div><p class="text-xl font-black sm:text-2xl">{{ $stat['value'] }}</p><p class="text-xs font-semibold text-slate-500 sm:text-sm">{{ $stat['label'] }}</p></div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section id="platform" class="scroll-mt-24 py-12 sm:py-16 lg:py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                        <p class="text-xs font-black uppercase text-amber-600 sm:text-sm">{{ __('messages.platform') }}</p>
                        <h2 class="mt-3 text-2xl font-black tracking-normal sm:text-4xl">{{ __('messages.platform_title') }}</h2>
                    </div>
                    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach([
                            ['icon' => 'dashboard', 'title' => __('messages.intelligent_dashboards'), 'text' => __('messages.intelligent_dashboards_text')],
                            ['icon' => 'calendar', 'title' => __('messages.smart_bookings'), 'text' => __('messages.smart_bookings_text')],
                            ['icon' => 'nfc', 'title' => __('messages.nfc_access'), 'text' => __('messages.nfc_access_feature_text')],
                            ['icon' => 'activity', 'title' => __('messages.progress_tracking'), 'text' => __('messages.progress_tracking_text')],
                        ] as $feature)
                            <div class="rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-1 hover:border-amber-300 dark:border-slate-800 dark:bg-slate-900 sm:rounded-2xl sm:p-5">
                                <span class="grid size-10 place-items-center rounded-lg bg-slate-100 text-amber-600 dark:bg-slate-800 dark:text-amber-300 sm:size-11 sm:rounded-xl"><x-icon name="{{ $feature['icon'] }}" size="21" /></span>
                                <h3 class="mt-4 text-base font-black sm:mt-5 sm:text-lg">{{ $feature['title'] }}</h3>
                                <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $feature['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="workflows" class="scroll-mt-24 border-y border-slate-200 bg-slate-50 py-12 dark:border-slate-800 dark:bg-slate-900 sm:py-16 lg:py-20">
                <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:px-8">
                    <div>
                        <p class="text-xs font-black uppercase text-amber-600 sm:text-sm">{{ __('messages.workflow') }}</p>
                        <h2 class="mt-3 text-2xl font-black tracking-normal sm:text-4xl">{{ __('messages.workflow_title') }}</h2>
                        <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-400 sm:text-base sm:leading-7">{{ __('messages.workflow_text_nfc') }}</p>
                    </div>
                    <div class="grid gap-3">
                        @foreach([
                            ['icon' => 'calendar', 'title' => __('messages.book_class'), 'text' => __('messages.super_admin_text')],
                            ['icon' => 'nfc', 'title' => __('messages.nfc_access'), 'text' => __('messages.gym_admin_text')],
                            ['icon' => 'coach', 'title' => __('messages.training_plans'), 'text' => __('messages.coach_text')],
                            ['icon' => 'activity', 'title' => __('messages.progress'), 'text' => __('messages.member_text')],
                        ] as $role)
                            <div class="flex gap-3 rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-800 dark:bg-slate-950 sm:gap-4 sm:rounded-2xl sm:p-4">
                                <span class="grid size-10 place-items-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 sm:size-12 sm:rounded-xl"><x-icon name="{{ $role['icon'] }}" size="22" /></span>
                                <div><h3 class="font-black">{{ $role['title'] }}</h3><p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $role['text'] }}</p></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="security" class="scroll-mt-24 py-12 sm:py-16 lg:py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid gap-8 lg:grid-cols-[1fr_1fr]">
                        <div>
                            <p class="text-xs font-black uppercase text-amber-600 sm:text-sm">{{ __('messages.security') }}</p>
                            <h2 class="mt-3 text-2xl font-black tracking-normal sm:text-4xl">{{ __('messages.security_title') }}</h2>
                            <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-400 sm:text-base sm:leading-7">{{ __('messages.security_text') }}</p>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach([
                                ['icon' => 'lock', 'title' => __('messages.auth_first_routes')],
                                ['icon' => 'shield', 'title' => __('messages.role_middleware')],
                                ['icon' => 'building', 'title' => __('messages.gym_status_gate')],
                                ['icon' => 'target', 'title' => __('messages.tenant_bindings')],
                            ] as $security)
                                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800 sm:rounded-2xl sm:p-5">
                                    <x-icon name="{{ $security['icon'] }}" size="24" class="text-amber-500" />
                                    <p class="mt-4 font-black">{{ $security['title'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section id="demo" class="scroll-mt-24 bg-amber-50 py-12 text-slate-950 dark:bg-slate-950 dark:text-white sm:py-16 lg:py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap items-end justify-between gap-5">
                        <div>
                            <p class="text-xs font-black uppercase text-amber-600 dark:text-amber-300 sm:text-sm">{{ __('messages.demo_ready') }}</p>
                            <h2 class="mt-3 text-2xl font-black tracking-normal sm:text-4xl">{{ __('messages.demo_ready_title') }}</h2>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-black text-slate-950 transition hover:bg-amber-400 sm:px-5 sm:py-3">
                            {{ __('messages.open_login') }}
                            <x-icon name="arrow-right" size="18" class="rtl:rotate-180" />
                        </a>
                    </div>
                    <div class="mt-8 grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                        @foreach([
                            [__('messages.dashboard'), __('messages.demo_super_text')],
                            [__('messages.courses'), __('messages.demo_admin_text')],
                            [__('messages.nfc_access'), __('messages.demo_member_text')],
                            [__('messages.progress'), __('messages.demo_coach_text')],
                        ] as $step)
                            <div class="rounded-xl border border-amber-200 bg-white p-4 dark:border-white/10 dark:bg-white/5 sm:rounded-2xl sm:p-5">
                                <p class="font-black text-amber-700 dark:text-amber-200">{{ $step[0] }}</p>
                                <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $step[1] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-200 py-8 dark:border-slate-800">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 text-sm text-slate-500 sm:px-6 lg:px-8">
                <x-application-logo />
                <p>Copyright © {{ now()->year }} <a href="https://github.com/med6ba" class="font-bold text-amber-600 hover:text-amber-500">Medba</a>. {{ __('messages.all_rights_reserved') }}</p>
            </div>
        </footer>
    </div>
</body>
</html>
