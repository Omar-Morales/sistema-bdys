<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlmacenPedidoController extends Controller
{
    public function index(Request $request): View
    {
        $almacenId = $request->user()?->almacen_id;

        abort_if(empty($almacenId), 403, 'El usuario no tiene un almacén asignado.');

        $pedidos = Pedido::with(['tienda', 'vendedor', 'almacen', 'encargado'])
            ->where('almacen_id', $almacenId)
            ->latest()
            ->paginate(20);

        return view('almacen.pedidos.index', compact('pedidos'));
    }

    public function show(Request $request, Pedido $pedido): View
    {
        $almacenId = $request->user()?->almacen_id;

        abort_if(empty($almacenId), 403, 'El usuario no tiene un almacén asignado.');
        abort_if((int) $pedido->almacen_id !== (int) $almacenId, 403);

        $pedido->load(['tienda', 'vendedor', 'almacen', 'encargado', 'detalles.producto']);
        $cambio = max((float) $pedido->monto_pagado - (float) $pedido->monto_total, 0);

        return view('almacen.pedidos.show', compact('pedido', 'cambio'));
    }
}
