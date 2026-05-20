<x-guest-layout>
    <x-slot name="title">{{ __('messages.login') }}</x-slot>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-5 sm:mb-6">
        <p class="text-xs font-black uppercase text-amber-300 sm:text-sm">{{ __('messages.welcome_back') }}</p>
        <h1 class="mt-2 text-2xl font-black tracking-normal text-white sm:text-3xl">{{ __('messages.login_title') }}</h1>
        <p class="mt-2 text-xs leading-5 text-slate-300 sm:text-sm sm:leading-6">{{ __('messages.login_subtitle') }}</p>
    </div>

    @php
        $demoAccounts = [
            ['key' => 'super_admin', 'label' => __('messages.super_admin'), 'email' => 'super@igym.com', 'icon' => 'shield'],
            ['key' => 'gym_admin', 'label' => __('messages.gym_admin'), 'email' => 'admin@igym.com', 'icon' => 'building'],
            ['key' => 'coach', 'label' => __('messages.coach'), 'email' => 'coach@igym.com', 'icon' => 'coach'],
            ['key' => 'reception', 'label' => __('messages.reception'), 'email' => 'reception@igym.com', 'icon' => 'scan'],
            ['key' => 'member', 'label' => __('messages.member'), 'email' => 'member@igym.com', 'icon' => 'user'],
        ];
    @endphp

    <form
        method="POST"
        action="{{ route('login') }}"
        x-data="{
            email: @js(old('email', '')),
            password: '',
            selectedDemo: null,
            selectDemo(role, email) {
                this.selectedDemo = role;
                this.email = email;
                this.password = 'password';
                this.$nextTick(() => this.$refs.login.focus());
            }
        }"
    >
        @csrf

        <div>
            <x-input-label for="email" :value="__('messages.email')" />
            <x-text-input id="email" x-model="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" placeholder="member@example.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.password')" />

            <x-text-input id="password" class="mt-1 block w-full"
                            x-model="password"
                            type="password"
                            name="password"
                            placeholder="{{ __('messages.password') }}"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-5 rounded-xl border border-amber-300/20 bg-amber-300/10 p-3 text-xs text-amber-50 sm:mt-6 sm:p-4 sm:text-sm">
            <p class="font-bold">{{ __('messages.demo_accounts') }}</p>
            <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach($demoAccounts as $account)
                    <button
                        type="button"
                        class="igym-focus flex min-h-11 items-center justify-center rounded-lg border px-3 py-2.5 text-center text-xs font-bold transition sm:text-sm"
                        x-on:click="selectDemo(@js($account['key']), @js($account['email']))"
                        x-bind:class="selectedDemo === @js($account['key']) ? 'border-amber-300 bg-amber-300 text-slate-950' : 'border-amber-200/20 bg-white/5 text-amber-50 hover:border-amber-300/70 hover:bg-amber-300/15'"
                        aria-label="{{ __('messages.login') }} {{ $account['label'] }}"
                    >
                        <span class="truncate">{{ $account['label'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="mt-5 flex items-center justify-end">
            <x-button x-ref="login" class="ms-3 gap-2" aria-label="{{ __('messages.login') }}" title="{{ __('messages.login') }}">
                <x-icon name="log-in" size="20" />
                <span>{{ __('messages.login') }}</span>
            </x-button>
        </div>
    </form>
</x-guest-layout>
