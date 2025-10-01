<?php

use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\ProfileController;
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
});

Route::middleware(['auth', 'role:Supervisor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('roles-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions.index');
    Route::put('roles-permissions', [RolePermissionController::class, 'update'])->name('roles.permissions.update');

    Route::resource('users', AdminUserController::class)->except(['show', 'destroy']);
    Route::get('users/{user}/permissions', [UserPermissionController::class, 'edit'])->name('users.permissions.edit');
    Route::put('users/{user}/permissions', [UserPermissionController::class, 'update'])->name('users.permissions.update');
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

Route::get('/supervisor', function () {
    return 'Acceso permitido: Supervisor';
})->middleware(['auth', 'role:Supervisor'])->name('supervisor');

Route::get('/encargado', function () {
    return 'Acceso permitido: Encargado';
})->middleware(['auth', 'role:Encargado'])->name('encargado');
