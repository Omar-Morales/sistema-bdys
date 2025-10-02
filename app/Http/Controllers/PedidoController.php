<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pedido\StorePedidoRequest;
use App\Http\Requests\Pedido\UpdatePedidoRequest;
use App\Models\Almacen;
use App\Models\Pedido;
use App\Models\Tienda;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(): View
    {
        $pedidos = Pedido::with(['tienda', 'vendedor', 'almacen', 'encargado'])
            ->latest()
            ->paginate(20);

        return view('pedidos.index', compact('pedidos'));
    }

    public function create(): View
    {
        return view('pedidos.create', $this->formData());
    }

    public function store(StorePedidoRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $cambio = $request->cambioCalculado();

        $pedido = Pedido::create($data);

        return redirect()
            ->route('supervisor.pedidos.show', $pedido)
            ->with('status', $this->mensajeExitoso('Pedido registrado correctamente.', $cambio));
    }

    public function show(Pedido $pedido): View
    {
        $pedido->load(['tienda', 'vendedor', 'almacen', 'encargado', 'detalles.producto']);
        $cambio = max((float) $pedido->monto_pagado - (float) $pedido->monto_total, 0);

        return view('pedidos.show', compact('pedido', 'cambio'));
    }

    public function edit(Pedido $pedido): View
    {
        $pedido->load(['tienda', 'vendedor', 'almacen', 'encargado']);

        return view('pedidos.edit', array_merge(['pedido' => $pedido], $this->formData()));
    }

    public function update(UpdatePedidoRequest $request, Pedido $pedido): RedirectResponse
    {
        $data = $request->validated();
        $cambio = $request->cambioCalculado();

        $pedido->update($data);

        return redirect()
            ->route('supervisor.pedidos.show', $pedido)
            ->with('status', $this->mensajeExitoso('Pedido actualizado correctamente.', $cambio));
    }

    public function destroy(Pedido $pedido): RedirectResponse
    {
        $pedido->delete();

        return redirect()->route('supervisor.pedidos.index')->with('status', 'Pedido eliminado correctamente.');
    }

    protected function formData(): array
    {
        $tiendas = Tienda::orderBy('nombre')->pluck('nombre', 'id');
        $vendedores = Vendedor::orderBy('nombre')->pluck('nombre', 'id');
        $almacenes = Almacen::orderBy('nombre')->pluck('nombre', 'id');
        $encargados = User::role('Encargado')->orderBy('name')->pluck('name', 'id');

        return [
            'tiendas' => $tiendas,
            'vendedores' => $vendedores,
            'almacenes' => $almacenes,
            'encargados' => $encargados,
            'tiposEntrega' => Pedido::TIPOS_ENTREGA,
            'tiposPago' => Pedido::TIPOS_PAGO,
            'estadosPedido' => Pedido::ESTADOS_PEDIDO,
        ];
    }

    protected function mensajeExitoso(string $mensajeBase, float $cambio): string
    {
        if ($cambio > 0) {
            return sprintf('%s Se registró un vuelto de S/ %s.', $mensajeBase, number_format($cambio, 2));
        }

        return $mensajeBase;
    }
}
