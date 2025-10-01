<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vendedor\StoreVendedorRequest;
use App\Http\Requests\Vendedor\UpdateVendedorRequest;
use App\Models\Tienda;
use App\Models\Vendedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VendedorController extends Controller
{
    public function index(): View
    {
        $vendedores = Vendedor::with('tienda')->orderBy('nombre')->paginate(15);

        return view('vendedores.index', compact('vendedores'));
    }

    public function create(): View
    {
        $tiendas = Tienda::orderBy('nombre')->pluck('nombre', 'id');

        return view('vendedores.create', compact('tiendas'));
    }

    public function store(StoreVendedorRequest $request): RedirectResponse
    {
        Vendedor::create($request->validated());

        return redirect()->route('vendedores.index')->with('status', 'Vendedor registrado correctamente.');
    }

    public function show(Vendedor $vendedor): View
    {
        $vendedor->load('tienda');

        return view('vendedores.show', compact('vendedor'));
    }

    public function edit(Vendedor $vendedor): View
    {
        $tiendas = Tienda::orderBy('nombre')->pluck('nombre', 'id');

        return view('vendedores.edit', compact('vendedor', 'tiendas'));
    }

    public function update(UpdateVendedorRequest $request, Vendedor $vendedor): RedirectResponse
    {
        $vendedor->update($request->validated());

        return redirect()->route('vendedores.index')->with('status', 'Vendedor actualizado correctamente.');
    }

    public function destroy(Vendedor $vendedor): RedirectResponse
    {
        $vendedor->delete();

        return redirect()->route('vendedores.index')->with('status', 'Vendedor eliminado correctamente.');
    }
}
