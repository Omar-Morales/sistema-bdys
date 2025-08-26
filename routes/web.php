<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Ruta de prueba para verificar rol
Route::get('/test', function () {
    /** @var User|null $user */
    $user = Auth::user();
    $rol  = $user ? $user->rol : null;

    return response()->json([
        'usuario'   => $user,
        'tiene_rol' => $rol ? $rol->nombre : 'No tiene rol',
    ]);
})->middleware(['auth']);

// Ruta protegida solo para adminö
Route::get('/admin', function () {
    return 'Acceso permitido: Admin';
})->middleware(['auth', 'role:admin'])->name('admin');

// Ruta protegida solo para encargado
Route::get('/encargado', function () {
    return 'Acceso permitido: Encargado';
})->middleware(['auth', 'role:encargado'])->name('encargado');

// Ruta protegida solo para almacen
Route::get('/almacen', function () {
    return 'Acceso permitido: Almacen';
})->middleware(['auth', 'role:almacen'])->name('almacen');
