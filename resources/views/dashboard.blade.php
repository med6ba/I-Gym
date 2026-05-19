<x-app-layout>
    <x-slot name="title">{{ __('messages.dashboard') }}</x-slot>
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <x-alert type="info">
            <a href="{{ role_home_route() }}" class="font-bold text-amber-700 dark:text-amber-300">{{ __('messages.dashboard') }}</a>
        </x-alert>
    </div>
</x-app-layout>
