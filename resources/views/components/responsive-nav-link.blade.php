@props(['active' => false])

@php
    $baseClasses = 'flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/70';

    $classes = $active
        ? 'bg-indigo-600 text-white shadow-md'
        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => trim($baseClasses . ' ' . $classes)]) }}>
    {{ $slot }}
</a>
