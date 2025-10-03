@props([
    'title' => null,
    'description' => null,
    'headers' => [],
])

@php
    $hasDescription = filled($description);
    $hasActions = isset($actions) && $actions instanceof \Illuminate\View\ComponentSlot && trim($actions->toHtml()) !== '';
    $hasFooter = isset($footer) && $footer instanceof \Illuminate\View\ComponentSlot && trim($footer->toHtml()) !== '';
@endphp

<div
    {{
        $attributes->merge([
            'class' => 'overflow-hidden rounded-2xl border border-slate-200 bg-white/95 shadow-sm',
        ])
    }}
>
    @if ($title || $hasDescription || $hasActions)
        <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                @if ($title)
                    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                @endif

                @if ($hasDescription)
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

    <div class="px-4 pb-6 pt-4 sm:px-6">
        <div class="overflow-hidden">
            <div class="max-w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    @if (! empty($headers))
                        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                @foreach ($headers as $header)
                                    <th scope="col" class="whitespace-nowrap px-6 py-3 {{ $header['class'] ?? 'text-left' }}">
                                        {{ $header['label'] }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                    @endif
                    <tbody class="divide-y divide-slate-200 bg-white">
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($hasFooter)
        <div class="border-t border-slate-200 bg-white/90 px-6 py-4">
            {{ $footer }}
        </div>
    @endif
</div>
