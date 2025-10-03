<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CierreAlmacenController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'fecha' => ['nullable', 'date_format:Y-m-d'],
            'almacen_id' => ['nullable', 'integer', 'exists:almacenes,id'],
        ]);

        $fechaInput = $validated['fecha'] ?? null;
        $almacenId = $validated['almacen_id'] ?? null;

        $fecha = null;

        if ($fechaInput !== null) {
            try {
                $fecha = Carbon::createFromFormat('Y-m-d', $fechaInput);
            } catch (Throwable $exception) {
                $fecha = null;
            }
        }

        $cierres = Pedido::query()
            ->selectRaw('DATE(pedidos.created_at) as fecha')
            ->selectRaw('pedidos.almacen_id as almacen_id')
            ->selectRaw('SUM(pedidos.monto_total) as total_monto')
            ->selectRaw('SUM(pedidos.monto_pagado) as total_pagado')
            ->selectRaw('SUM(pedidos.saldo_pendiente) as total_pendiente')
            ->selectRaw('SUM(CASE WHEN pedidos.monto_pagado > pedidos.monto_total THEN pedidos.monto_pagado - pedidos.monto_total ELSE 0 END) as total_vuelto')
            ->leftJoin('almacenes', 'almacenes.id', '=', 'pedidos.almacen_id')
            ->addSelect('almacenes.nombre as almacen_nombre')
            ->when($fecha, fn ($query) => $query->whereDate('pedidos.created_at', $fecha))
            ->when($almacenId, fn ($query) => $query->where('pedidos.almacen_id', $almacenId))
            ->groupBy('fecha', 'pedidos.almacen_id', 'almacenes.nombre')
            ->orderByDesc('fecha')
            ->orderBy('almacenes.nombre')
            ->paginate(15)
            ->through(function ($cierre) {
                if (! empty($cierre->fecha)) {
                    try {
                        $cierre->fecha = Carbon::createFromFormat('Y-m-d', (string) $cierre->fecha);
                    } catch (Throwable $exception) {
                        $cierre->fecha = null;
                    }
                }

                return $cierre;
            });

        $almacenes = Almacen::orderBy('nombre')->pluck('nombre', 'id');

        return view('cierres.index', compact('cierres', 'fecha', 'almacenId', 'almacenes'));
    }
}
