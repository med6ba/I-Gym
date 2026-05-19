@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 focus:border-amber-500 focus:ring-amber-500']) }}>
