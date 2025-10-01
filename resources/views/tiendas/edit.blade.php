<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar tienda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('tiendas.update', $tienda) }}">
                    @csrf
                    @method('PUT')
                    @include('tiendas._form', ['submitLabel' => __('Actualizar')])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
