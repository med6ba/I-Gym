<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-lg border border-amber-500 bg-amber-500 px-4 py-2.5 text-xs font-black uppercase tracking-normal text-slate-950 transition hover:border-amber-400 hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900']) }}>
    {{ $slot }}
</button>
