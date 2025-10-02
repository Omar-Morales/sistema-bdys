<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
            {{ __('Pedidos asignados a mi almacén') }}
        </h1>
    </x-slot>

    <section class="space-y-6">
        @php
            $estadoPedidoStyles = [
                \App\Models\Pedido::ESTADO_PEDIDO_ENTREGADO => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                \App\Models\Pedido::ESTADO_PEDIDO_EN_CURSO => 'bg-sky-50 text-sky-600 ring-sky-100',
                \App\Models\Pedido::ESTADO_PEDIDO_PENDIENTE => 'bg-amber-50 text-amber-600 ring-amber-100',
                \App\Models\Pedido::ESTADO_PEDIDO_ANULADO => 'bg-rose-50 text-rose-600 ring-rose-100',
            ];
        @endphp

        <x-tailadmin.table-card
            :title="__('Pedidos en seguimiento')"
            :description="__('Listado de pedidos asignados al almacén para control y despacho.')"
            :headers="[
                ['label' => __('Código')],
                ['label' => __('Tienda')],
                ['label' => __('Monto total')],
                ['label' => __('Estado pedido')],
                ['label' => __('Acciones'), 'class' => 'text-right'],
            ]"
        >
            @forelse ($pedidos as $pedido)
                <tr class="odd:bg-white even:bg-slate-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">#{{ $pedido->id }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $pedido->tienda?->nombre }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">S/ {{ number_format($pedido->monto_total, 2) }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                        @php
                            $estadoPedidoClass = $estadoPedidoStyles[$pedido->estado_pedido] ?? 'bg-slate-50 text-slate-600 ring-slate-100';
                        @endphp
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $estadoPedidoClass }}">
                            {{ ucwords(str_replace('_', ' ', $pedido->estado_pedido)) }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                        <a href="{{ route('almacen.pedidos.show', $pedido) }}"
                            class="inline-flex items-center gap-1 rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-sm font-medium text-indigo-600 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('Ver detalle') }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-slate-500">
                        {{ __('No hay pedidos asignados a este almacén.') }}
                    </td>
                </tr>
            @endforelse

            @if ($pedidos->hasPages())
                <x-slot:footer>
                    {{ $pedidos->links() }}
                </x-slot:footer>
            @endif
        </x-tailadmin.table-card>
    </section>
</x-app-layout>
