<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                {{ __('Pedidos') }}
            </h1>
            @can('manage pedidos')
                <a href="{{ route('supervisor.pedidos.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Registrar pedido') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <section class="space-y-6">
        @if (session('status'))
            <x-tailadmin.alert type="success">
                {{ session('status') }}
            </x-tailadmin.alert>
        @endif

        @php
            $estadoPagoStyles = [
                \App\Models\Pedido::ESTADO_PAGO_PAGADO => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                \App\Models\Pedido::ESTADO_PAGO_VUELTO => 'bg-sky-50 text-sky-600 ring-sky-100',
                \App\Models\Pedido::ESTADO_PAGO_POR_COBRAR => 'bg-amber-50 text-amber-600 ring-amber-100',
                \App\Models\Pedido::ESTADO_PAGO_PENDIENTE => 'bg-amber-50 text-amber-600 ring-amber-100',
            ];

            $estadoPedidoStyles = [
                \App\Models\Pedido::ESTADO_PEDIDO_ENTREGADO => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                \App\Models\Pedido::ESTADO_PEDIDO_EN_CURSO => 'bg-sky-50 text-sky-600 ring-sky-100',
                \App\Models\Pedido::ESTADO_PEDIDO_PENDIENTE => 'bg-amber-50 text-amber-600 ring-amber-100',
                \App\Models\Pedido::ESTADO_PEDIDO_ANULADO => 'bg-rose-50 text-rose-600 ring-rose-100',
            ];
        @endphp

        <x-tailadmin.table-card
            :title="__('Pedidos registrados')"
            :description="__('Consulta y gestiona los pedidos capturados en el sistema.')"
            :headers="[
                ['label' => __('Código')],
                ['label' => __('Tienda')],
                ['label' => __('Vendedor')],
                ['label' => __('Monto total')],
                ['label' => __('Estado pago')],
                ['label' => __('Estado pedido')],
                ['label' => __('Acciones'), 'class' => 'text-right'],
            ]"
        >
            @forelse ($pedidos as $pedido)
                <tr class="odd:bg-white even:bg-slate-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">#{{ $pedido->id }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $pedido->tienda?->nombre }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $pedido->vendedor?->nombre }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">S/ {{ number_format($pedido->monto_total, 2) }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                        @php
                            $estadoPagoClass = $estadoPagoStyles[$pedido->estado_pago] ?? 'bg-slate-50 text-slate-600 ring-slate-100';
                        @endphp
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $estadoPagoClass }}">
                            {{ __(ucwords(str_replace('_', ' ', $pedido->estado_pago))) }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                        @php
                            $estadoPedidoClass = $estadoPedidoStyles[$pedido->estado_pedido] ?? 'bg-slate-50 text-slate-600 ring-slate-100';
                        @endphp
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $estadoPedidoClass }}">
                            {{ __(ucwords(str_replace('_', ' ', $pedido->estado_pedido))) }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('supervisor.pedidos.show', $pedido) }}"
                                class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2">
                                {{ __('Ver') }}
                            </a>
                            @can('manage pedidos')
                                <a href="{{ route('supervisor.pedidos.edit', $pedido) }}"
                                    class="inline-flex items-center gap-1 rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-sm font-medium text-indigo-600 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    {{ __('Editar') }}
                                </a>
                                <form action="{{ route('supervisor.pedidos.destroy', $pedido) }}" method="POST"
                                    class="inline"
                                    onsubmit="return confirm('{{ __('¿Eliminar este pedido?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-sm font-medium text-rose-600 shadow-sm transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                                        {{ __('Eliminar') }}
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-slate-500">
                        {{ __('No se encontraron pedidos.') }}
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
