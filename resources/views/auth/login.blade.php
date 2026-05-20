<x-guest-layout>
    <x-slot name="title">{{ __('messages.login') }}</x-slot>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-5 sm:mb-6">
        <p class="text-xs font-black uppercase text-amber-300 sm:text-sm">{{ __('messages.welcome_back') }}</p>
        <h1 class="mt-2 text-2xl font-black tracking-normal text-white sm:text-3xl">{{ __('messages.login_title') }}</h1>
        <p class="mt-2 text-xs leading-5 text-slate-300 sm:text-sm sm:leading-6">{{ __('messages.login_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('messages.email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" placeholder="member@example.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.password')" />

            <x-text-input id="password" class="mt-1 block w-full"
                            type="password"
                            name="password"
                            placeholder="{{ __('messages.password') }}"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-5 flex items-center justify-end">
            <x-button class="ms-3 gap-2" aria-label="{{ __('messages.login') }}" title="{{ __('messages.login') }}">
                <x-icon name="log-in" size="20" />
                <span>{{ __('messages.login') }}</span>
            </x-button>
        </div>
    </form>

    <div class="mt-5 rounded-xl border border-amber-300/20 bg-amber-300/10 p-3 text-xs text-amber-50 sm:mt-6 sm:p-4 sm:text-sm">
        <p class="font-bold">{{ __('messages.demo_accounts') }}</p>
        <p class="mt-2">super@igym.com / password</p>
        <p>admin@igym.com / password</p>
        <p>coach@igym.com / password</p>
        <p>reception@igym.com / password</p>
        <p>member@igym.com / password</p>
    </div>
</x-guest-layout>
