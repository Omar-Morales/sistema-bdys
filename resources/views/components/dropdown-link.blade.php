<a
    {{
        $attributes->merge([
            'class' => 'flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400',
        ])
    }}
>
    {{ $slot }}
</a>
