<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.language') }}</h2></x-slot>
    <div class="mx-auto max-w-3xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        <form method="POST" action="{{ route('settings.language.update') }}" class="igym-card space-y-4 p-5">
            @csrf
            <label class="block"><span class="text-sm font-bold">{{ __('messages.language') }}</span><select name="language" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach(['en'=>'English','fr'=>'Français','es'=>'Español','ar'=>'العربية'] as $code => $label)<option value="{{ $code }}" @selected(app()->getLocale()===$code)>{{ $label }}</option>@endforeach</select></label>
            <x-button>{{ __('messages.save') }}</x-button>
        </form>
    </div>
</x-app-layout>
