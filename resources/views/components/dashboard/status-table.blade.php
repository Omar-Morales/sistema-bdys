@props([
    'title' => null,
    'rows' => [],
    'columns' => [],
    'emptyMessage' => 'No hay datos disponibles.',
])

<div
    {{
        $attributes->merge([
            'class' => 'flex flex-col rounded-2xl border border-slate-200 bg-white shadow-sm',
        ])
    }}
>
    <div class="border-b border-slate-200 px-6 py-5">
        @if ($title)
            <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
        @endif

        @if (! $slot->isEmpty())
            <p class="mt-2 text-sm text-slate-500">
                {{ $slot }}
            </p>
        @endif
    </div>

    <div class="px-2 pb-6 pt-4 sm:px-4">
        <div class="overflow-hidden">
            <div class="max-w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th scope="col" class="whitespace-nowrap px-6 py-3 text-left">
                                {{ __('Estado') }}
                            </th>
                            @foreach ($columns as $column)
                                <th
                                    scope="col"
                                    class="whitespace-nowrap px-6 py-3 {{ $column['headerClass'] ?? 'text-left' }}"
                                >
                                    {{ $column['label'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($rows as $row)
                            <tr class="odd:bg-white even:bg-slate-50 hover:bg-slate-100/70">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-700">
                                    {{ $row['label'] }}
                                </td>
                                @foreach ($columns as $column)
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 {{ $column['class'] ?? '' }}">
                                        {{ data_get($row['values'], $column['key']) }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="{{ count($columns) + 1 }}"
                                    class="whitespace-nowrap px-6 py-4 text-center text-sm text-slate-500"
                                >
                                    {{ $emptyMessage }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
