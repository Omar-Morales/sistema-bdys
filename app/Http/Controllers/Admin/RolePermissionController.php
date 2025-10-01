<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        $modules = config('modules.menus');

        return view('admin.roles.index', compact('roles', 'permissions', 'modules'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['array'],
            'roles.*.*' => ['string', 'exists:'.config('permission.table_names.permissions').',name'],
        ]);

        $rolesData = $validated['roles'] ?? [];
        $allRoles = Role::orderBy('id')->get();

        foreach ($allRoles as $role) {
            $permissionNames = $rolesData[$role->id] ?? [];
            $role->syncPermissions($permissionNames);
        }

        return redirect()->route('admin.roles.permissions.index')->with('status', 'Permisos actualizados correctamente.');
    }
}
