@props([
    'title' => null,
    'description' => null,
])

@php
    use Illuminate\\View\\ComponentSlot;

    $hasActions = isset($actions) && $actions instanceof ComponentSlot && trim($actions->toHtml()) !== '';
    $hasFooter = isset($footer) && $footer instanceof ComponentSlot && trim($footer->toHtml()) !== '';
@endphp

<div
    {{
        $attributes->merge([
            'class' => 'rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm',
        ])
    }}
>
    @if ($title || filled($description) || $hasActions)
        <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                @if ($title)
                    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                @endif

                @if (filled($description))
                    <p class="text-sm text-slate-500">{{ $description }}</p>
                @endif
            </div>

            @if ($hasActions)
                <div class="flex flex-wrap items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="mt-5 space-y-6">
        {{ $slot }}
    </div>

    @if ($hasFooter)
        <div class="mt-6 border-t border-slate-200 pt-4">
            {{ $footer }}
        </div>
    @endif
</div>
