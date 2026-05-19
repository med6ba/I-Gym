@php($current = app()->getLocale())

<form method="POST" action="{{ route('settings.language.update') }}">
    @csrf
    <label class="sr-only" for="language-switcher">{{ __('messages.language') }}</label>
    <select id="language-switcher" name="language" onchange="this.form.submit()" class="igym-focus rounded-lg border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
        <option value="en" @selected($current === 'en')>EN</option>
        <option value="fr" @selected($current === 'fr')>FR</option>
        <option value="es" @selected($current === 'es')>ES</option>
        <option value="ar" @selected($current === 'ar')>AR</option>
    </select>
</form>
