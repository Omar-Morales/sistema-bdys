@props([
    'title' => null,
    'value' => null,
    'subtitle' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow rounded-lg']) }}>
    <div class="p-5">
        <div class="flex items-center">
            @if ($icon)
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-indigo-100 text-indigo-600">
                        {!! $icon !!}
                    </span>
                </div>
            @endif
            <div class="{{ $icon ? 'ml-5' : '' }}">
                <dt class="text-sm font-medium text-gray-500 truncate">{{ $title }}</dt>
                <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $value }}</dd>
                @if ($subtitle)
                    <p class="mt-2 text-sm text-gray-500">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    </div>
    @if (! $slot->isEmpty())
        <div class="bg-gray-50 px-5 py-3 text-sm text-gray-500">
            {{ $slot }}
        </div>
    @endif
</div>
