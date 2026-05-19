<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.create_gym_customer') }}</h2></x-slot>
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('super.gyms.store') }}" class="igym-card p-5">
            @include('super.gyms._form')
        </form>
    </div>
</x-app-layout>
