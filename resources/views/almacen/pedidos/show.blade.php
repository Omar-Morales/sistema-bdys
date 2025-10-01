<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del pedido #:id', ['id' => $pedido->id]) }}
            </h2>
            <a href="{{ route('almacen.pedidos.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                {{ __('Volver') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Tienda') }}</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $pedido->tienda?->nombre }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Vendedor') }}</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $pedido->vendedor?->nombre }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Estado del pedido') }}</h3>
                            <span class="inline-flex mt-1 items-center px-2 py-1 rounded-full text-xs font-semibold {{ $pedido->estado_pedido === \App\Models\Pedido::ESTADO_PEDIDO_ENTREGADO ? 'bg-green-100 text-green-800' : ($pedido->estado_pedido === \App\Models\Pedido::ESTADO_PEDIDO_ANULADO ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucwords(str_replace('_', ' ', $pedido->estado_pedido)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Tipo de entrega') }}</h3>
                            <p class="mt-1 text-gray-900">{{ ucwords(str_replace('_', ' ', $pedido->tipo_entrega)) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Monto total') }}</h3>
                            <p class="mt-1 text-lg text-gray-900">S/ {{ number_format($pedido->monto_total, 2) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Saldo pendiente') }}</h3>
                            <p class="mt-1 text-lg text-gray-900">S/ {{ number_format($pedido->saldo_pendiente, 2) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Vuelto') }}</h3>
                            <p class="mt-1 text-lg text-gray-900">S/ {{ number_format($cambio, 2) }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Notas') }}</h3>
                        <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $pedido->notas ?: __('Sin notas adicionales') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Detalle de productos') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Producto') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Cantidad') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Metraje') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pedido->detalles as $detalle)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detalle->producto?->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detalle->cantidad }} {{ $detalle->unidad }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detalle->metraje ?? __('N/A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detalle->subtotal ? 'S/ '.number_format($detalle->subtotal, 2) : __('N/A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">{{ __('No hay productos registrados para este pedido.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
