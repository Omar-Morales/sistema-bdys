<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public const CRUD_MODULES = [
        'categorias',
        'productos',
        'tiendas',
        'vendedores',
        'pedidos',
    ];

    public function run(): void
    {
        $modules = config('modules.menus');

        collect($modules)
            ->map(fn (array $module, string $key) => $this->normalizePermissions($module, $key))
            ->flatten()
            ->unique()
            ->each(fn (string $permission) => Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]));
    }

    /**
     * @return array<int, string>
     */
    private function normalizePermissions(array $module, string $moduleKey): array
    {
        $permissions = $module['permissions'] ?? [];

        if (!isset($permissions['view'])) {
            $permissions = ['view' => $module['permission'] ?? 'view '.$moduleKey] + $permissions;
        }

        return array_values(Arr::whereNotNull($permissions));
    }
}
