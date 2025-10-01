<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $categoria->nombre }}
            </h2>
            <a href="{{ route('categorias.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                {{ __('Volver al listado') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Nombre') }}</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $categoria->nombre }}</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Creado el') }}</h3>
                            <p class="mt-1 text-gray-900">{{ $categoria->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Actualizado el') }}</h3>
                            <p class="mt-1 text-gray-900">{{ $categoria->updated_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
