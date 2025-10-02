<button
    {{
        $attributes->merge([
            'type' => 'submit',
            'class' => 'inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition-colors duration-150 hover:bg-indigo-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/70 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60',
        ])
    }}
>
    {{ $slot }}
</button>
