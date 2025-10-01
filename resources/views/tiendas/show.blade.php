<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $tienda->nombre }}
            </h2>
            <a href="{{ route('tiendas.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                {{ __('Volver al listado') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Sector') }}</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $tienda->sector }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Teléfono') }}</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $tienda->telefono }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Dirección') }}</h3>
                            <p class="mt-1 text-gray-900">{{ $tienda->direccion }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Creado el') }}</h3>
                            <p class="mt-1 text-gray-900">{{ $tienda->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Actualizado el') }}</h3>
                            <p class="mt-1 text-gray-900">{{ $tienda->updated_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Vendedores asociados') }}</h3>
                    <div class="space-y-2">
                        @forelse ($tienda->vendedores as $vendedor)
                            <div class="flex items-center justify-between rounded-md border border-gray-200 px-4 py-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $vendedor->nombre }}</p>
                                    <p class="text-sm text-gray-500">{{ $vendedor->telefono }}</p>
                                </div>
                                <a href="{{ route('vendedores.show', $vendedor) }}" class="text-sm text-indigo-600 hover:text-indigo-800">{{ __('Ver detalle') }}</a>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">{{ __('No hay vendedores registrados para esta tienda.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
