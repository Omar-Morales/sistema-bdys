<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Vendedores') }}
            </h2>
            @can('manage vendedores')
                <a href="{{ route('vendedores.create') }}"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Agregar vendedor') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    @if (session('status'))
                        <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nombre') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tienda') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Teléfono') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Acciones') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($vendedores as $vendedor)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="{{ route('vendedores.show', $vendedor) }}" class="text-indigo-600 hover:text-indigo-900">{{ $vendedor->nombre }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vendedor->tienda?->nombre ?? __('Sin tienda') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vendedor->telefono }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            @can('manage vendedores')
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('vendedores.edit', $vendedor) }}"
                                                        class="inline-flex items-center rounded-md border border-indigo-200 bg-white px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-indigo-600 transition duration-150 ease-in-out hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                        {{ __('Actualizar') }}
                                                    </a>
                                                    <form action="{{ route('vendedores.destroy', $vendedor) }}" method="POST"
                                                        onsubmit="return confirm('{{ __('¿Eliminar este vendedor?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center rounded-md border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-red-600 transition duration-150 ease-in-out hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                            {{ __('Eliminar') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">{{ __('No se encontraron vendedores.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $vendedores->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
