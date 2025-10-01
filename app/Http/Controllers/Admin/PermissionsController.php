<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $modules = config('modules.menus');

        return view('admin.permissions.index', compact('roles', 'modules'));
    }

    public function update(Request $request): RedirectResponse
    {
        $modules = collect(config('modules.menus'));
        $modulePermissions = $modules->pluck('permission')->filter()->values();

        $validated = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['array'],
            'roles.*.*' => ['string', Rule::in($modulePermissions->toArray())],
        ]);

        $rolesData = $validated['roles'] ?? [];

        foreach (Role::with('permissions')->get() as $role) {
            $selectedPermissions = array_values(array_unique($rolesData[$role->id] ?? []));
            $currentPermissions = $role->permissions->pluck('name')->toArray();

            $nonModulePermissions = array_diff($currentPermissions, $modulePermissions->toArray());
            $role->syncPermissions(array_merge($nonModulePermissions, $selectedPermissions));
        }

        return redirect()->route('admin.permissions.index')->with('status', 'Permisos de visualización actualizados.');
    }
}
