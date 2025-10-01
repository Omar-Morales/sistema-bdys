<?php

use App\Http\Controllers\Admin\PermissionsController as AdminPermissionsController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\AlmacenPedidoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CierreAlmacenController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\VendedorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'permission:view dashboard'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categorias', CategoriaController::class)
        ->only(['index', 'show'])
        ->middleware('permission:view categorias');
    Route::resource('categorias', CategoriaController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('permission:manage categorias');

    Route::resource('productos', ProductoController::class)
        ->only(['index', 'show'])
        ->middleware('permission:view productos');
    Route::resource('productos', ProductoController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('permission:manage productos');

    Route::resource('tiendas', TiendaController::class)
        ->only(['index', 'show'])
        ->middleware('permission:view tiendas');
    Route::resource('tiendas', TiendaController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('permission:manage tiendas');

    Route::resource('vendedores', VendedorController::class)
        ->only(['index', 'show'])
        ->middleware('permission:view vendedores');
    Route::resource('vendedores', VendedorController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('permission:manage vendedores');
});

Route::middleware(['auth', 'role:Supervisor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('roles-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions.index');
    Route::put('roles-permissions', [RolePermissionController::class, 'update'])->name('roles.permissions.update');

    Route::get('permissions', [AdminPermissionsController::class, 'index'])->name('permissions.index');
    Route::put('permissions', [AdminPermissionsController::class, 'update'])->name('permissions.update');

    Route::resource('users', AdminUserController::class)->except(['show', 'destroy']);
    Route::get('users/{user}/permissions', [UserPermissionController::class, 'edit'])->name('users.permissions.edit');
    Route::put('users/{user}/permissions', [UserPermissionController::class, 'update'])->name('users.permissions.update');
});

Route::middleware(['auth', 'role:Supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::resource('pedidos', PedidoController::class)
        ->only(['index', 'show'])
        ->middleware('permission:view pedidos');
    Route::resource('pedidos', PedidoController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('permission:manage pedidos');

    Route::get('cierres', [CierreAlmacenController::class, 'index'])
        ->middleware('permission:view cierres')
        ->name('cierres.index');
});

Route::middleware(['auth', 'role:Encargado', 'permission:view pedidos almacenes'])->prefix('almacen')->name('almacen.')->group(function () {
    Route::get('pedidos', [AlmacenPedidoController::class, 'index'])->name('pedidos.index');
    Route::get('pedidos/{pedido}', [AlmacenPedidoController::class, 'show'])->name('pedidos.show');
});

require __DIR__.'/auth.php';

Route::get('/test', function () {
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $roles = $user ? $user->getRoleNames()->implode(', ') : null;

    return response()->json([
        'usuario' => $user,
        'roles' => $roles ?: 'Sin roles',
        'permisos' => $user ? $user->getPermissionNames() : [],
    ]);
})->middleware(['auth']);

