<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $producto->nombre }}
            </h2>
            <a href="{{ route('productos.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                {{ __('Volver al listado') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Categoría') }}</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $producto->categoria?->nombre ?? __('Sin categoría') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Unidad') }}</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ ucfirst($producto->unidad) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Medida') }}</h3>
                            <p class="mt-1 text-gray-900">{{ $producto->medida ?? __('No especificado') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Piezas por caja') }}</h3>
                            <p class="mt-1 text-gray-900">{{ $producto->piezas_por_caja }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Precio referencial') }}</h3>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">S/ {{ number_format($producto->precio_referencial, 2) }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Creado el') }}</h3>
                            <p class="mt-1 text-gray-900">{{ $producto->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Actualizado el') }}</h3>
                            <p class="mt-1 text-gray-900">{{ $producto->updated_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
