@php($theme = auth()->user()->theme ?? 'light')

<label class="sr-only" for="theme-toggle">{{ __('messages.theme') }}</label>
<select id="theme-toggle" onchange="window.igymSetTheme(this.value)" class="igym-focus rounded-lg border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
    <option value="light" @selected($theme === 'light')>{{ __('messages.light') }}</option>
    <option value="dark" @selected($theme === 'dark')>{{ __('messages.dark') }}</option>
    <option value="system" @selected($theme === 'system')>{{ __('messages.system') }}</option>
</select>
