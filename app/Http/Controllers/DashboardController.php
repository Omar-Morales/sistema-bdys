<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Cobro;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as BaseQueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $shouldFilterByWarehouse = $this->shouldFilterByWarehouse($user);

        $pedidosQuery = Pedido::query();

        if ($shouldFilterByWarehouse && $user->almacen_id) {
            $pedidosQuery->where('almacen_id', $user->almacen_id);
        }

        $totalesGenerales = (clone $pedidosQuery)
            ->selectRaw('COUNT(*) as total_pedidos')
            ->selectRaw('COALESCE(SUM(monto_total), 0) as monto_total')
            ->selectRaw('COALESCE(SUM(monto_pagado), 0) as monto_pagado')
            ->selectRaw('COALESCE(SUM(saldo_pendiente), 0) as saldo_pendiente')
            ->first();

        $pedidosPorEstado = $this->aggregateByEstado(
            clone $pedidosQuery,
            'estado_pedido',
            Pedido::ESTADOS_PEDIDO,
            [
                'monto_total' => 'COALESCE(SUM(monto_total), 0)',
                'monto_pagado' => 'COALESCE(SUM(monto_pagado), 0)',
            ]
        );

        $cobrosQuery = Cobro::query()->whereHas('pedido', function ($query) use ($shouldFilterByWarehouse, $user) {
            /** @var Builder|BaseQueryBuilder $query */
            if ($shouldFilterByWarehouse && $user->almacen_id) {
                $query->where('almacen_id', $user->almacen_id);
            }
        });

        $resumenPagos = $this->aggregateByEstado(
            $cobrosQuery,
            'estado_pago',
            Cobro::ESTADOS_PAGO,
            [
                'monto' => 'COALESCE(SUM(monto), 0)',
                'monto_pagado' => 'COALESCE(SUM(monto_pagado), 0)',
            ]
        );

        $metricasPorAlmacen = $this->metricasPorAlmacen($user, $shouldFilterByWarehouse);

        return view('dashboard', [
            'totalesGenerales' => [
                'total_pedidos' => (int) ($totalesGenerales->total_pedidos ?? 0),
                'monto_total' => (float) ($totalesGenerales->monto_total ?? 0),
                'monto_pagado' => (float) ($totalesGenerales->monto_pagado ?? 0),
                'saldo_pendiente' => (float) ($totalesGenerales->saldo_pendiente ?? 0),
            ],
            'pedidosPorEstado' => $pedidosPorEstado,
            'resumenPagos' => $resumenPagos,
            'metricasPorAlmacen' => $metricasPorAlmacen,
            'mostrandoGlobal' => ! $shouldFilterByWarehouse,
        ]);
    }

    protected function shouldFilterByWarehouse(User $user): bool
    {
        if ($user->hasRole('Supervisor')) {
            return false;
        }

        return $user->hasRole('Encargado') || (! empty($user->almacen_id) && ! $user->hasRole('Supervisor'));
    }

    protected function aggregateByEstado($query, string $column, array $estados, array $sumColumns = []): array
    {
        $select = [DB::raw('COUNT(*) as total'), $column];

        foreach ($sumColumns as $alias => $expression) {
            $select[] = DB::raw($expression.' as '.$alias);
        }

        $resultados = $query
            ->select($select)
            ->groupBy($column)
            ->get()
            ->keyBy($column);

        $base = [];

        foreach ($estados as $estado) {
            $base[$estado] = [
                'total' => 0,
            ];

            foreach (array_keys($sumColumns) as $alias) {
                $base[$estado][$alias] = 0.0;
            }
        }

        foreach ($resultados as $estado => $row) {
            $base[$estado]['total'] = (int) $row->total;

            foreach (array_keys($sumColumns) as $alias) {
                $base[$estado][$alias] = (float) $row->{$alias};
            }
        }

        return $base;
    }

    protected function metricasPorAlmacen(User $user, bool $shouldFilterByWarehouse): Collection
    {
        $query = Pedido::query();

        if ($shouldFilterByWarehouse && $user->almacen_id) {
            $query->where('almacen_id', $user->almacen_id);
        }

        $agrupados = $query
            ->select('almacen_id')
            ->selectRaw('COUNT(*) as total_pedidos')
            ->selectRaw('COALESCE(SUM(monto_total), 0) as monto_total')
            ->selectRaw('COALESCE(SUM(monto_pagado), 0) as monto_pagado')
            ->groupBy('almacen_id')
            ->get();

        $almacenIds = $agrupados->pluck('almacen_id')->filter()->all();
        $almacenes = Almacen::whereIn('id', $almacenIds)->get()->keyBy('id');

        return $agrupados->map(function ($row) use ($almacenes) {
            $almacen = $almacenes->get($row->almacen_id);

            return [
                'almacen_id' => $row->almacen_id,
                'almacen' => $almacen?->nombre ?? 'Sin asignar',
                'total_pedidos' => (int) $row->total_pedidos,
                'monto_total' => (float) $row->monto_total,
                'monto_pagado' => (float) $row->monto_pagado,
                'saldo_pendiente' => (float) round((float) $row->monto_total - (float) $row->monto_pagado, 2),
            ];
        })->values();
    }
}
