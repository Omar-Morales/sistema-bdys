<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    public function edit(User $user): View
    {
        $permissions = Permission::orderBy('name')->get();
        $modules = config('modules.menus');
        $userPermissions = $user->getPermissionNames()->toArray();

        return view('admin.users.permissions', compact('user', 'permissions', 'modules', 'userPermissions'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:'.config('permission.table_names.permissions').',name'],
        ]);

        $user->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.users.index')->with('status', 'Permisos personalizados actualizados.');
    }
}
