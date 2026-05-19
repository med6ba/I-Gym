<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.theme') }}</h2></x-slot>
    <div class="mx-auto max-w-3xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        <form method="POST" action="{{ route('settings.theme.update') }}" class="igym-card space-y-4 p-5">
            @csrf @method('PATCH')
            <label class="block"><span class="text-sm font-bold">{{ __('messages.theme') }}</span><select name="theme" onchange="window.igymSetTheme(this.value)" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach(['light'=>__('messages.light'),'dark'=>__('messages.dark'),'system'=>__('messages.system')] as $code => $label)<option value="{{ $code }}" @selected((auth()->user()->theme ?? 'light')===$code)>{{ $label }}</option>@endforeach</select></label>
            <x-button>Save</x-button>
        </form>
    </div>
</x-app-layout>
