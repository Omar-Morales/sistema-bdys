<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tienda\StoreTiendaRequest;
use App\Http\Requests\Tienda\UpdateTiendaRequest;
use App\Models\Tienda;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TiendaController extends Controller
{
    public function index(): View
    {
        $tiendas = Tienda::orderBy('nombre')->paginate(15);

        return view('tiendas.index', compact('tiendas'));
    }

    public function create(): View
    {
        return view('tiendas.create');
    }

    public function store(StoreTiendaRequest $request): RedirectResponse
    {
        Tienda::create($request->validated());

        return redirect()->route('tiendas.index')->with('status', 'Tienda registrada correctamente.');
    }

    public function show(Tienda $tienda): View
    {
        $tienda->load('vendedores');

        return view('tiendas.show', compact('tienda'));
    }

    public function edit(Tienda $tienda): View
    {
        return view('tiendas.edit', compact('tienda'));
    }

    public function update(UpdateTiendaRequest $request, Tienda $tienda): RedirectResponse
    {
        $tienda->update($request->validated());

        return redirect()->route('tiendas.index')->with('status', 'Tienda actualizada correctamente.');
    }

    public function destroy(Tienda $tienda): RedirectResponse
    {
        $tienda->delete();

        return redirect()->route('tiendas.index')->with('status', 'Tienda eliminada correctamente.');
    }
}
