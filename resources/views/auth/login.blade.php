<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <p class="text-sm font-black uppercase text-amber-600 dark:text-amber-300">{{ __('messages.welcome_back') }}</p>
        <h1 class="mt-2 text-3xl font-black tracking-normal text-slate-950 dark:text-white">{{ __('messages.login_title') }}</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('messages.login_subtitle') }}</p>
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
            <x-button class="ms-3 grid size-10 place-items-center !px-0 !py-0" aria-label="{{ __('messages.login') }}" title="{{ __('messages.login') }}">
                <x-icon name="log-in" size="20" />
            </x-button>
        </div>
    </form>

    <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-300/20 dark:bg-amber-300/10 dark:text-amber-50">
        <p class="font-bold">{{ __('messages.demo_accounts') }}</p>
        <p class="mt-2">super@igym.com / password</p>
        <p>admin@igym.com / password</p>
        <p>coach@igym.com / password</p>
        <p>reception@igym.com / password</p>
        <p>member@igym.com / password</p>
    </div>
</x-guest-layout>
