<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                {{ __('Panel de control') }}
            </h1>
            <p class="text-sm text-slate-500">
                {{ $mostrandoGlobal ? __('Mostrando métricas globales') : __('Métricas filtradas por tu almacén asignado') }}
            </p>
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

    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 2xl:grid-cols-4">
        <x-tailadmin.metric-card
            :title="__('Pedidos registrados')"
            :value="$formatNumber($totalesGenerales['total_pedidos'] ?? 0)"
            :subtitle="__('Pedidos capturados en el sistema')"
            icon-classes="bg-indigo-100 text-indigo-600"
            icon="<svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M3 7h18M3 12h18M3 17h18'/></svg>"
        />

        <x-tailadmin.metric-card
            :title="__('Monto total')"
            :value="$formatCurrency($totalesGenerales['monto_total'] ?? 0)"
            :subtitle="__('Ingresos esperados por pedidos')"
            icon-classes="bg-emerald-100 text-emerald-600"
            icon="<svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-12c2.21 0 4 1.343 4 3m-4-3V4m0 16v-2'/></svg>"
        />

        <x-tailadmin.metric-card
            :title="__('Monto cobrado')"
            :value="$formatCurrency($totalesGenerales['monto_pagado'] ?? 0)"
            :subtitle="__('Pagos aplicados a pedidos')"
            icon-classes="bg-sky-100 text-sky-600"
            icon="<svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M9 12l2 2 4-4m4.5 2a9.5 9.5 0 11-19 0 9.5 9.5 0 0119 0z'/></svg>"
        />

        <x-tailadmin.metric-card
            :title="__('Saldo pendiente')"
            :value="$formatCurrency(($totalesGenerales['monto_total'] ?? 0) - ($totalesGenerales['monto_pagado'] ?? 0))"
            :subtitle="__('Importe restante por cobrar')"
            icon-classes="bg-amber-100 text-amber-600"
            icon="<svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
        />
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <x-tailadmin.data-table-card
            :title="__('Pedidos por estado')"
            :label-column="__('Estado')"
            :rows="$pedidosRows"
            :columns="[
                ['key' => 'total', 'label' => __('Pedidos'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ['key' => 'monto_total', 'label' => __('Monto total'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ['key' => 'monto_pagado', 'label' => __('Monto cobrado'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ['key' => 'saldo', 'label' => __('Saldo pendiente'), 'class' => 'text-right', 'headerClass' => 'text-right'],
            ]"
        >
            {{ __('Estados consolidados de los pedidos registrados.') }}
        </x-tailadmin.data-table-card>

        <x-tailadmin.data-table-card
            :title="__('Resumen de pagos')"
            :label-column="__('Estado de pago')"
            :rows="$pagosRows"
            :columns="[
                ['key' => 'total', 'label' => __('Cobros'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ['key' => 'monto', 'label' => __('Monto registrado'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ['key' => 'monto_pagado', 'label' => __('Monto pagado'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ['key' => 'saldo', 'label' => __('Saldo'), 'class' => 'text-right', 'headerClass' => 'text-right'],
            ]"
        >
            {{ __('Detalle de cobros pendientes y completados según estado de pago.') }}
        </x-tailadmin.data-table-card>
    </section>

    <section>
        <x-tailadmin.data-table-card
            :title="$mostrandoGlobal ? __('Rendimiento por almacén') : __('Resumen de tu almacén')"
            :label-column="__('Almacén')"
            :rows="$almacenRows"
            :columns="[
                ['key' => 'total', 'label' => __('Pedidos'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ['key' => 'monto_total', 'label' => __('Monto total'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ['key' => 'monto_pagado', 'label' => __('Monto cobrado'), 'class' => 'text-right', 'headerClass' => 'text-right'],
                ['key' => 'saldo', 'label' => __('Saldo pendiente'), 'class' => 'text-right', 'headerClass' => 'text-right'],
            ]"
        >
            {{ $mostrandoGlobal ? __('Comparativo de desempeño entre almacenes.') : __('Detalle de movimientos del almacén asignado.') }}
        </x-tailadmin.data-table-card>
    </section>
</x-app-layout>
