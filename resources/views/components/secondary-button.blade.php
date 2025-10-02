<button
    {{
        $attributes->merge([
            'type' => 'button',
            'class' => 'inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-600 shadow-sm transition-colors duration-150 hover:border-indigo-400 hover:text-indigo-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/70 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60',
        ])
    }}
>
    {{ $slot }}
</button>
