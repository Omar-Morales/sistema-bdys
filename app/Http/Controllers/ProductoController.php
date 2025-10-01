<?php

namespace App\Http\Controllers;

use App\Http\Requests\Producto\StoreProductoRequest;
use App\Http\Requests\Producto\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::with('categoria')->orderBy('nombre')->paginate(15);

        return view('productos.index', compact('productos'));
    }

    public function create(): View
    {
        $categorias = Categoria::orderBy('nombre')->pluck('nombre', 'id');

        return view('productos.create', [
            'categorias' => $categorias,
            'unidades' => Producto::UNIDADES,
        ]);
    }

    public function store(StoreProductoRequest $request): RedirectResponse
    {
        Producto::create($request->validated());

        return redirect()->route('productos.index')->with('status', 'Producto registrado correctamente.');
    }

    public function show(Producto $producto): View
    {
        $producto->load('categoria');

        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto): View
    {
        $categorias = Categoria::orderBy('nombre')->pluck('nombre', 'id');

        return view('productos.edit', [
            'producto' => $producto,
            'categorias' => $categorias,
            'unidades' => Producto::UNIDADES,
        ]);
    }

    public function update(UpdateProductoRequest $request, Producto $producto): RedirectResponse
    {
        $producto->update($request->validated());

        return redirect()->route('productos.index')->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();

        return redirect()->route('productos.index')->with('status', 'Producto eliminado correctamente.');
    }
}
