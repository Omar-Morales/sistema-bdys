@props([
    'type' => 'info',
    'title' => null,
])

@php
    $palettes = [
        'info' => [
            'container' => 'border-sky-200 bg-sky-50 text-sky-700',
            'icon' => 'text-sky-500',
        ],
        'success' => [
            'container' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'icon' => 'text-emerald-500',
        ],
        'warning' => [
            'container' => 'border-amber-200 bg-amber-50 text-amber-700',
            'icon' => 'text-amber-500',
        ],
        'danger' => [
            'container' => 'border-rose-200 bg-rose-50 text-rose-700',
            'icon' => 'text-rose-500',
        ],
    ];

    $palette = $palettes[$type] ?? $palettes['info'];
@endphp

<div
    {{
        $attributes->merge([
            'class' => 'flex items-start gap-3 rounded-2xl border px-4 py-3 text-sm shadow-sm '.$palette['container'],
        ])
    }}
>
    <div class="mt-0.5">
        <svg class="h-5 w-5 {{ $palette['icon'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12V16.5zm9-4.5a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <div class="space-y-1">
        @if ($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif

        <div class="text-sm leading-relaxed">
            {{ $slot }}
        </div>
    </div>
</div>
