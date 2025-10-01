@props([
    'title' => null,
    'rows' => [],
    'columns' => [],
    'emptyMessage' => 'No hay datos disponibles.',
])

<div {{ $attributes->merge(['class' => 'bg-white shadow rounded-lg']) }}>
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $title }}</h3>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        @foreach ($columns as $column)
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $column['headerClass'] ?? '' }}">
                                {{ $column['label'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($rows as $row)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $row['label'] }}
                            </td>
                            @foreach ($columns as $column)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 {{ $column['class'] ?? '' }}">
                                    {{ data_get($row['values'], $column['key']) }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ $emptyMessage }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (! $slot->isEmpty())
            <div class="mt-4 text-sm text-gray-500">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
