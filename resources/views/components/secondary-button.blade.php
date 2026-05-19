<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold uppercase tracking-normal text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:opacity-25 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-amber-700 dark:hover:bg-amber-950/30 dark:focus:ring-offset-slate-900']) }}>
    {{ $slot }}
</button>
