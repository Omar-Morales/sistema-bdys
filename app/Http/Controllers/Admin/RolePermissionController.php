<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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

        $modulePermissions = $this->modulePermissions();
        $rolesData = $validated['roles'] ?? [];
        $allRoles = Role::with('permissions')->orderBy('id')->get();

        foreach ($allRoles as $role) {
            $submitted = collect($rolesData[$role->id] ?? []);
            $updated = collect($submitted);

            foreach ($modulePermissions as $permissions) {
                $viewPermission = $permissions['view'] ?? null;
                $managePermission = $permissions['manage'] ?? null;

                if ($viewPermission && $submitted->contains($viewPermission) && $managePermission) {
                    $updated->push($managePermission);
                }

                if ($managePermission && $submitted->contains($managePermission) && $viewPermission && !$submitted->contains($viewPermission)) {
                    $updated->push($viewPermission);
                }
            }

            $role->syncPermissions($updated->unique()->values()->all());
        }

        return redirect()->route('admin.roles.permissions.index')->with('status', 'Permisos actualizados correctamente.');
    }

    private function modulePermissions(): Collection
    {
        return collect(config('modules.menus'))
            ->map(fn (array $module, string $key) => $this->normalizePermissions($module, $key));
    }

    private function normalizePermissions(array $module, string $moduleKey): array
    {
        $permissions = $module['permissions'] ?? [];

        if (!isset($permissions['view'])) {
            $permissions = ['view' => $module['permission'] ?? 'view '.$moduleKey] + $permissions;
        }

        return Arr::whereNotNull($permissions);
    }
}
