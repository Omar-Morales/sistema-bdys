<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Panel de control') }}
            </h2>
            <span class="inline-flex items-center text-sm text-gray-500">
                {{ $mostrandoGlobal ? __('Mostrando métricas globales') : __('Métricas filtradas por tu almacén asignado') }}
            </span>
        </div>
    </x-slot>

    @php
        $formatCurrency = fn ($value) => 'S/ '.number_format((float) $value, 2, '.', ',');
        $formatNumber = fn ($value) => number_format((int) $value, 0, '.', ',');

        $estadoPedidoLabels = [
            \App\Models\Pedido::ESTADO_PEDIDO_PENDIENTE => __('Pendiente'),
            \App\Models\Pedido::ESTADO_PEDIDO_EN_CURSO => __('En curso'),
            \App\Models\Pedido::ESTADO_PEDIDO_ENTREGADO => __('Entregado'),
            \App\Models\Pedido::ESTADO_PEDIDO_ANULADO => __('Anulado'),
        ];

        $estadoPagoLabels = [
            \App\Models\Cobro::ESTADO_PAGO_PENDIENTE => __('Pendiente'),
            \App\Models\Cobro::ESTADO_PAGO_POR_COBRAR => __('Por cobrar'),
            \App\Models\Cobro::ESTADO_PAGO_VUELTO => __('Con vuelto'),
            \App\Models\Cobro::ESTADO_PAGO_PAGADO => __('Pagado'),
        ];

        $pedidosRows = collect($pedidosPorEstado)->map(function ($values, $estado) use ($estadoPedidoLabels, $formatNumber, $formatCurrency) {
            $saldo = ($values['monto_total'] ?? 0) - ($values['monto_pagado'] ?? 0);

            return [
                'label' => $estadoPedidoLabels[$estado] ?? ucfirst(str_replace('_', ' ', $estado)),
                'values' => [
                    'total' => $formatNumber($values['total'] ?? 0),
                    'monto_total' => $formatCurrency($values['monto_total'] ?? 0),
                    'monto_pagado' => $formatCurrency($values['monto_pagado'] ?? 0),
                    'saldo' => $formatCurrency($saldo),
                ],
            ];
        })->values()->all();

        $pagosRows = collect($resumenPagos)->map(function ($values, $estado) use ($estadoPagoLabels, $formatNumber, $formatCurrency) {
            $saldo = ($values['monto'] ?? 0) - ($values['monto_pagado'] ?? 0);

            return [
                'label' => $estadoPagoLabels[$estado] ?? ucfirst(str_replace('_', ' ', $estado)),
                'values' => [
                    'total' => $formatNumber($values['total'] ?? 0),
                    'monto' => $formatCurrency($values['monto'] ?? 0),
                    'monto_pagado' => $formatCurrency($values['monto_pagado'] ?? 0),
                    'saldo' => $formatCurrency($saldo),
                ],
            ];
        })->values()->all();

        $almacenRows = collect($metricasPorAlmacen)->map(function ($row) use ($formatNumber, $formatCurrency) {
            return [
                'label' => $row['almacen'] ?? __('Sin asignar'),
                'values' => [
                    'total' => $formatNumber($row['total_pedidos'] ?? 0),
                    'monto_total' => $formatCurrency($row['monto_total'] ?? 0),
                    'monto_pagado' => $formatCurrency($row['monto_pagado'] ?? 0),
                    'saldo' => $formatCurrency($row['saldo_pendiente'] ?? 0),
                ],
            ];
        })->all();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <x-dashboard.stat-card
                    :title="__('Pedidos registrados')"
                    :value="$formatNumber($totalesGenerales['total_pedidos'] ?? 0)"
                    :subtitle="__('Pedidos capturados en el sistema')"
                    icon="<svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M3 7h18M3 12h18M3 17h18'/></svg>"
                />

                <x-dashboard.stat-card
                    :title="__('Monto total')"
                    :value="$formatCurrency($totalesGenerales['monto_total'] ?? 0)"
                    :subtitle="__('Ingresos esperados por pedidos')"
                    icon="<svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-12c2.21 0 4 1.343 4 3m-4-3V4m0 16v-2'/></svg>"
                />

                <x-dashboard.stat-card
                    :title="__('Monto cobrado')"
                    :value="$formatCurrency($totalesGenerales['monto_pagado'] ?? 0)"
                    :subtitle="__('Pagos aplicados a pedidos')"
                    icon="<svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M9 12l2 2 4-4m4.5 2a9.5 9.5 0 11-19 0 9.5 9.5 0 0119 0z'/></svg>"
                />

                <x-dashboard.stat-card
                    :title="__('Saldo pendiente')"
                    :value="$formatCurrency(($totalesGenerales['monto_total'] ?? 0) - ($totalesGenerales['monto_pagado'] ?? 0))"
                    :subtitle="__('Importe restante por cobrar')"
                    icon="<svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                />
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <x-dashboard.status-table
                    :title="__('Pedidos por estado')"
                    :rows="$pedidosRows"
                    :columns="[
                        ['key' => 'total', 'label' => __('Pedidos'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                        ['key' => 'monto_total', 'label' => __('Monto total'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                        ['key' => 'monto_pagado', 'label' => __('Monto cobrado'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                        ['key' => 'saldo', 'label' => __('Saldo pendiente'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                    ]"
                >
                    {{ __('Estados consolidados de los pedidos registrados.') }}
                </x-dashboard.status-table>

                <x-dashboard.status-table
                    :title="__('Resumen de pagos')"
                    :rows="$pagosRows"
                    :columns="[
                        ['key' => 'total', 'label' => __('Cobros'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                        ['key' => 'monto', 'label' => __('Monto registrado'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                        ['key' => 'monto_pagado', 'label' => __('Monto pagado'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                        ['key' => 'saldo', 'label' => __('Saldo'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                    ]"
                >
                    {{ __('Detalle de cobros pendientes y completados según estado de pago.') }}
                </x-dashboard.status-table>
            </div>

            <x-dashboard.status-table
                :title="$mostrandoGlobal ? __('Rendimiento por almacén') : __('Resumen de tu almacén')"
                :rows="$almacenRows"
                :columns="[
                    ['key' => 'total', 'label' => __('Pedidos'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                    ['key' => 'monto_total', 'label' => __('Monto total'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                    ['key' => 'monto_pagado', 'label' => __('Monto cobrado'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                    ['key' => 'saldo', 'label' => __('Saldo pendiente'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ]"
            >
                {{ $mostrandoGlobal ? __('Comparativo de desempeño entre almacenes.') : __('Detalle de movimientos del almacén asignado.') }}
            </x-dashboard.status-table>
        </div>
    </div>
</x-app-layout>
