@props([
    'title' => null,
    'value' => null,
    'subtitle' => null,
    'icon' => null,
    'badge' => null,
    'badgeColor' => 'primary',
    'iconClasses' => 'bg-indigo-100 text-indigo-600',
])

@php
    use Illuminate\View\ComponentSlot;

    $iconContent = $icon instanceof ComponentSlot ? $icon->toHtml() : $icon;
    $hasIcon = filled(trim((string) $iconContent ?? ''));

    $badgeContent = $badge instanceof ComponentSlot ? $badge->toHtml() : $badge;
    $hasBadge = filled(trim((string) $badgeContent ?? ''));

    $badgePalettes = [
        'primary' => 'bg-indigo-100 text-indigo-600',
        'success' => 'bg-emerald-100 text-emerald-600',
        'warning' => 'bg-amber-100 text-amber-600',
        'danger' => 'bg-rose-100 text-rose-600',
        'info' => 'bg-sky-100 text-sky-600',
    ];

    $badgeClasses = $badgePalettes[$badgeColor] ?? $badgePalettes['primary'];
@endphp

<div
    {{
        $attributes->merge([
            'class' => 'group rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md',
        ])
    }}
>
    <div class="flex items-start justify-between gap-4">
        <div class="flex items-center gap-4">
            @if ($hasIcon)
                <div class="flex h-14 w-14 items-center justify-center rounded-full {{ $iconClasses }}">
                    {!! $iconContent !!}
                </div>
            @endif

            <div class="flex-1 space-y-2">
                @if ($title)
                    <p class="text-sm font-medium text-slate-500">{{ $title }}</p>
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    @isset($value)
                        <p class="text-2xl font-semibold text-slate-900">{{ $value }}</p>
                    @endisset

                    @if ($hasBadge)
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClasses }}">
                            {!! $badgeContent !!}
                        </span>
                    @endif
                </div>

                @if ($subtitle)
                    <p class="text-sm text-slate-500">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    </div>

    @if (! $slot->isEmpty())
        <div class="mt-5 border-t border-slate-200 pt-4 text-sm text-slate-500">
            {{ $slot }}
        </div>
    @endif
</div>
