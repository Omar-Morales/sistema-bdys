<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar pedido') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('supervisor.pedidos.store') }}">
                    @include('pedidos._form', ['submitLabel' => __('Guardar pedido')])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
