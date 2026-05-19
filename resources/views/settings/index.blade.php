<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.settings') }}</h2></x-slot>
    <div class="mx-auto max-w-3xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif

        <div x-data="{ language: '{{ app()->getLocale() }}', theme: '{{ auth()->user()->theme ?? 'light' }}', originalLanguage: '{{ app()->getLocale() }}', originalTheme: '{{ auth()->user()->theme ?? 'light' }}' }" class="space-y-6">
            <div class="igym-card p-5 sm:p-6">
                <div class="flex items-start gap-4">
                    <span class="grid size-12 place-items-center rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300">
                        <x-icon name="globe" size="24" />
                    </span>
                    <div>
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.language') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.language_help') }}</p>
                    </div>
                </div>
                <label class="igym-field mt-4 block">
                    <span class="igym-label">{{ __('messages.language') }}</span>
                    <select x-model="language" class="igym-input">
                        <option value="en">English (EN)</option>
                        <option value="fr">Français (FR)</option>
                        <option value="es">Español (ES)</option>
                        <option value="ar">العربية (AR)</option>
                    </select>
                </label>
            </div>

            <div class="igym-card p-5 sm:p-6">
                <div class="flex items-start gap-4">
                    <span class="grid size-12 place-items-center rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300">
                        <x-icon name="palette" size="24" />
                    </span>
                    <div>
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.theme') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.theme_help') }}</p>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-3">
                    <template x-for="(option, key) in { light: '{{ __('messages.light') }}', dark: '{{ __('messages.dark') }}', system: '{{ __('messages.system') }}' }" :key="key">
                        <button type="button" x-on:click="theme = key" x-bind:class="theme === key ? 'border-amber-300 bg-amber-50 dark:border-amber-800 dark:bg-amber-950/40' : 'border-slate-200 dark:border-slate-700'" class="flex flex-col items-center gap-2 rounded-xl border p-4 text-sm font-bold transition hover:border-amber-300 dark:hover:border-amber-800">
                            <span x-show="key === 'light'"><x-icon name="sun" size="22" /></span>
                            <span x-show="key === 'dark'"><x-icon name="moon" size="22" /></span>
                            <span x-show="key === 'system'"><x-icon name="monitor" size="22" /></span>
                            <span x-text="option"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ url()->previous() }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-bold dark:border-slate-700">{{ __('messages.cancel') }}</a>
                <button type="button" x-on:click="
                    fetch('{{ route('settings.save') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ language, theme })
                    }).then(r => {
                        if (r.ok) {
                            if (theme !== originalTheme) window.igymSetTheme(theme);
                            if (language !== originalLanguage) window.location.reload();
                            else window.location.reload();
                        }
                    })
                " class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 hover:bg-amber-400">
                    <x-icon name="save" size="17" />
                    {{ __('messages.save') }}
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
