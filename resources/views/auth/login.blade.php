<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <p class="text-sm font-black uppercase text-amber-300">{{ __('messages.welcome_back') }}</p>
        <h1 class="mt-2 text-3xl font-black tracking-normal text-white">{{ __('messages.login_title') }}</h1>
        <p class="mt-2 text-sm leading-6 text-slate-300">{{ __('messages.login_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('messages.email')" class="text-slate-200" />
            <x-text-input id="email" class="mt-1 block w-full border-white/10 bg-white/10 text-white placeholder:text-slate-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.password')" class="text-slate-200" />

            <x-text-input id="password" class="mt-1 block w-full border-white/10 bg-white/10 text-white placeholder:text-slate-400"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-white/10 text-amber-500 shadow-sm focus:ring-amber-500 focus:ring-offset-slate-950" name="remember">
                <span class="ms-2 text-sm text-slate-300">{{ __('messages.remember_me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="rounded-md text-sm font-semibold text-slate-300 underline decoration-white/20 underline-offset-4 hover:text-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-950" href="{{ route('password.request') }}">
                    {{ __('messages.forgot_password') }}
                </a>
            @endif

            <x-button class="ms-3">
                {{ __('messages.login') }}
            </x-button>
        </div>
    </form>

    <div class="mt-6 rounded-xl border border-amber-300/20 bg-amber-300/10 p-4 text-sm text-amber-50">
        <p class="font-bold">{{ __('messages.demo_accounts') }}</p>
        <p class="mt-2">super@igym.com / password</p>
        <p>admin@igym.com / password</p>
        <p>coach@igym.com / password</p>
        <p>reception@igym.com / password</p>
        <p>member@igym.com / password</p>
    </div>
</x-guest-layout>
