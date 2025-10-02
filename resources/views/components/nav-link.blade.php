@props(['active'])

@php
$classes = ($active ?? false)
            ? 'group flex items-center gap-3 rounded-lg bg-slate-800 px-3 py-2 text-sm font-semibold text-white shadow-sm transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400'
            : 'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition-colors duration-150 hover:bg-slate-800 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
