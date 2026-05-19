@php
    $languageOptions = [
        'en' => 'English',
        'fr' => 'Français',
        'es' => 'Español',
        'ar' => 'العربية',
    ];
    $themeOptions = [
        'light' => __('messages.light'),
        'dark' => __('messages.dark'),
        'system' => __('messages.system'),
    ];
    $currencyOptions = [
        'MAD' => 'MAD',
        'USD' => 'USD',
        'EUR' => 'EUR',
        'GBP' => 'GBP',
    ];
    $genderOptions = [
        'female' => __('messages.female'),
        'male' => __('messages.male'),
        'other' => __('messages.other'),
        'prefer_not_to_say' => __('messages.prefer_not_to_say'),
    ];
    $goalOptions = [
        'weight_loss' => __('messages.goal_weight_loss'),
        'muscle_gain' => __('messages.goal_muscle_gain'),
        'fitness' => __('messages.goal_fitness'),
        'endurance' => __('messages.goal_endurance'),
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold uppercase text-amber-600 dark:text-amber-300">{{ __('messages.profile_overview') }}</p>
                <h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.profile') }}</h2>
            </div>
        </div>
    </x-slot>

    <div
        class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8"
        x-data="{
            editing: {{ $errors->getBag('default')->isNotEmpty() ? 'true' : 'false' }},
            passwordOpen: {{ $errors->updatePassword->isNotEmpty() ? 'true' : 'false' }},
            dangerOpen: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }}
        }"
    >
        @if (session('status') === 'profile-updated')
            <x-alert type="success">{{ __('messages.profile_saved') }}</x-alert>
        @endif

        <div class="grid gap-6 lg:grid-cols-[0.75fr_1.25fr]">
            <section class="igym-card overflow-hidden">
                <div class="bg-slate-950 px-6 py-8 text-white">
                    <div class="flex items-center gap-4">
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="size-24 rounded-2xl border-4 border-white/15 object-cover shadow-xl shadow-slate-950/30">
                        <div class="min-w-0">
                            <p class="truncate text-2xl font-black">{{ $user->name }}</p>
                            <p class="mt-1 truncate text-sm font-semibold text-slate-300">{{ $user->email }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <x-badge status="info">{{ __('messages.'.$user->role) }}</x-badge>
                                <x-badge :status="$user->status" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 p-6">
                    <div>
                        <p class="text-xs font-black uppercase text-slate-400">{{ __('messages.account_preferences') }}</p>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                                <p class="text-xs font-bold text-slate-500">{{ __('messages.language') }}</p>
                                <p class="mt-1 font-black text-slate-950 dark:text-white">{{ $languageOptions[$user->language] ?? strtoupper($user->language) }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                                <p class="text-xs font-bold text-slate-500">{{ __('messages.theme') }}</p>
                                <p class="mt-1 font-black text-slate-950 dark:text-white">{{ $themeOptions[$user->theme] ?? __('messages.light') }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                                <p class="text-xs font-bold text-slate-500">{{ __('messages.currency') }}</p>
                                <p class="mt-1 font-black text-slate-950 dark:text-white">{{ $user->currency ?? 'MAD' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-black uppercase text-slate-400">{{ __('messages.body_metrics') }}</p>
                        <div class="mt-3 grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                                <p class="text-xs font-bold text-slate-500">{{ __('messages.age') }}</p>
                                <p class="mt-1 font-black text-slate-950 dark:text-white">{{ $user->age ?? __('messages.not_set') }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                                <p class="text-xs font-bold text-slate-500">{{ __('messages.height') }}</p>
                                <p class="mt-1 font-black text-slate-950 dark:text-white">{{ $user->height_cm ? number_format((float) $user->height_cm, 0).' cm' : __('messages.not_set') }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                                <p class="text-xs font-bold text-slate-500">{{ __('messages.weight') }}</p>
                                <p class="mt-1 font-black text-slate-950 dark:text-white">{{ $user->weight_kg ? number_format((float) $user->weight_kg, 1).' kg' : __('messages.not_set') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="space-y-6">
                <div class="igym-card p-5 sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.personal_information') }}</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.profile_locked_hint') }}</p>
                        </div>
                        <button type="button" x-on:click="editing = ! editing" class="igym-focus inline-flex items-center gap-2 rounded-lg border border-amber-500 bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 transition hover:border-amber-400 hover:bg-amber-400">
                            <x-icon name="edit" size="17" />
                            <span x-text="editing ? @js(__('messages.cancel')) : @js(__('messages.edit_profile'))"></span>
                        </button>
                    </div>

                    <div x-show="! editing" x-transition class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-500">{{ __('messages.phone') }}</p>
                            <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $user->phone ?: __('messages.not_set') }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-500">{{ __('messages.gender') }}</p>
                            <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $user->gender ? ($genderOptions[$user->gender] ?? Str::headline($user->gender)) : __('messages.not_set') }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-500">{{ __('messages.goal') }}</p>
                            <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $user->fitness_goal ? ($goalOptions[$user->fitness_goal] ?? Str::headline($user->fitness_goal)) : __('messages.not_set') }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-500">{{ __('messages.gym') }}</p>
                            <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $user->gym?->name ?? __('messages.global_saas') }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 md:col-span-2 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-500">{{ __('messages.bio') }}</p>
                            <p class="mt-1 text-sm leading-6 text-slate-700 dark:text-slate-300">{{ $user->bio ?: __('messages.not_set') }}</p>
                        </div>
                    </div>

                    <div x-cloak x-show="editing" x-transition class="mt-6">
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                                <div class="flex flex-wrap items-center gap-4">
                                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="size-20 rounded-2xl object-cover">
                                    <div class="min-w-0 flex-1">
                                        <x-input-label for="avatar" :value="__('messages.profile_photo')" />
                                        <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm text-slate-600 file:me-4 file:rounded-lg file:border-0 file:bg-amber-500 file:px-4 file:py-2 file:text-sm file:font-black file:text-slate-950 hover:file:bg-amber-400 dark:text-slate-300" />
                                        <p class="mt-2 text-xs text-slate-500">{{ __('messages.upload_avatar_help') }}</p>
                                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <x-input-label for="name" :value="__('messages.name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autocomplete="name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('messages.email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <div>
                                    <x-input-label for="phone" :value="__('messages.phone')" />
                                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>

                                <div>
                                    <x-input-label for="gender" :value="__('messages.gender')" />
                                    <select id="gender" name="gender" class="mt-1 block w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 focus:border-amber-500 focus:ring-amber-500">
                                        <option value="">{{ __('messages.not_set') }}</option>
                                        @foreach($genderOptions as $value => $label)
                                            <option value="{{ $value }}" @selected(old('gender', $user->gender) === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-3">
                                <div>
                                    <x-input-label for="language" :value="__('messages.language')" />
                                    <select id="language" name="language" class="mt-1 block w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 focus:border-amber-500 focus:ring-amber-500">
                                        @foreach($languageOptions as $value => $label)
                                            <option value="{{ $value }}" @selected(old('language', $user->language) === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('language')" />
                                </div>

                                <div>
                                    <x-input-label for="theme" :value="__('messages.theme')" />
                                    <select id="theme" name="theme" class="mt-1 block w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 focus:border-amber-500 focus:ring-amber-500">
                                        @foreach($themeOptions as $value => $label)
                                            <option value="{{ $value }}" @selected(old('theme', $user->theme) === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('theme')" />
                                </div>

                                <div>
                                    <x-input-label for="currency" :value="__('messages.currency')" />
                                    <select id="currency" name="currency" class="mt-1 block w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 focus:border-amber-500 focus:ring-amber-500">
                                        @foreach($currencyOptions as $value => $label)
                                            <option value="{{ $value }}" @selected(old('currency', $user->currency ?? 'MAD') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <x-icon name="activity" size="18" class="text-amber-500" />
                                    <h4 class="font-black text-slate-950 dark:text-white">{{ __('messages.body_metrics') }}</h4>
                                </div>
                                <p class="mt-1 text-sm text-slate-500">{{ __('messages.member_metrics_help') }}</p>

                                <div class="mt-4 grid gap-4 md:grid-cols-4">
                                    <div>
                                        <x-input-label for="age" :value="__('messages.age')" />
                                        <x-text-input id="age" name="age" type="number" min="10" max="100" class="mt-1 block w-full" :value="old('age', $user->age)" />
                                        <x-input-error class="mt-2" :messages="$errors->get('age')" />
                                    </div>

                                    <div>
                                        <x-input-label for="height_cm" :value="__('messages.height')" />
                                        <x-text-input id="height_cm" name="height_cm" type="number" min="80" max="260" step="0.1" class="mt-1 block w-full" :value="old('height_cm', $user->height_cm)" />
                                        <x-input-error class="mt-2" :messages="$errors->get('height_cm')" />
                                    </div>

                                    <div>
                                        <x-input-label for="weight_kg" :value="__('messages.weight')" />
                                        <x-text-input id="weight_kg" name="weight_kg" type="number" min="25" max="350" step="0.1" class="mt-1 block w-full" :value="old('weight_kg', $user->weight_kg)" />
                                        <x-input-error class="mt-2" :messages="$errors->get('weight_kg')" />
                                    </div>

                                    <div>
                                        <x-input-label for="fitness_goal" :value="__('messages.goal')" />
                                        <select id="fitness_goal" name="fitness_goal" class="mt-1 block w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 focus:border-amber-500 focus:ring-amber-500">
                                            <option value="">{{ __('messages.not_set') }}</option>
                                            @foreach($goalOptions as $value => $label)
                                                <option value="{{ $value }}" @selected(old('fitness_goal', $user->fitness_goal) === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('fitness_goal')" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="bio" :value="__('messages.bio')" />
                                <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 focus:border-amber-500 focus:ring-amber-500">{{ old('bio', $user->bio) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                            </div>

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <x-alert type="warning">
                                    {{ __('messages.email_unverified') }}
                                    <button form="send-verification" class="ms-1 font-black underline">{{ __('messages.resend_verification') }}</button>
                                </x-alert>

                                @if (session('status') === 'verification-link-sent')
                                    <x-alert type="success">{{ __('messages.verification_sent') }}</x-alert>
                                @endif
                            @endif

                            <div class="flex flex-wrap items-center justify-end gap-3">
                                <button type="button" x-on:click="editing = false" class="igym-focus rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30">
                                    {{ __('messages.cancel') }}
                                </button>
                                <button class="igym-focus inline-flex items-center gap-2 rounded-lg border border-amber-500 bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 transition hover:border-amber-400 hover:bg-amber-400">
                                    <x-icon name="save" size="17" />
                                    {{ __('messages.save_profile') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="igym-card p-5 sm:p-6">
                    <button type="button" x-on:click="passwordOpen = ! passwordOpen" class="flex w-full items-center justify-between gap-4 text-start">
                        <span>
                            <span class="block text-lg font-black text-slate-950 dark:text-white">{{ __('messages.change_password') }}</span>
                            <span class="mt-1 block text-sm text-slate-500 dark:text-slate-400">{{ __('messages.account_security_help') }}</span>
                        </span>
                        <x-icon name="chevron-down" size="18" class="transition" x-bind:class="passwordOpen ? 'rotate-180' : ''" />
                    </button>
                    <div x-cloak x-show="passwordOpen" x-transition class="mt-6 max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="igym-card border-rose-200 p-5 dark:border-rose-900/70 sm:p-6">
                    <button type="button" x-on:click="dangerOpen = ! dangerOpen" class="flex w-full items-center justify-between gap-4 text-start">
                        <span>
                            <span class="block text-lg font-black text-rose-700 dark:text-rose-300">{{ __('messages.danger_zone') }}</span>
                            <span class="mt-1 block text-sm text-slate-500 dark:text-slate-400">{{ __('messages.delete_account_help') }}</span>
                        </span>
                        <x-icon name="chevron-down" size="18" class="transition" x-bind:class="dangerOpen ? 'rotate-180' : ''" />
                    </button>
                    <div x-cloak x-show="dangerOpen" x-transition class="mt-6 max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
