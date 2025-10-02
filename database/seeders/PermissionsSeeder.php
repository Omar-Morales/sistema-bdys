<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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

        $moduleKeys = array_keys($modules);

        collect($modules)
            ->map(fn (array $module, string $key) => $module['permission'] ?? 'view '.$key)
            ->each(fn (string $permission) => Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]));

        collect(self::CRUD_MODULES)
            ->filter(fn (string $module) => in_array($module, $moduleKeys, true))
            ->map(fn (string $module) => 'manage '.$module)
            ->each(fn (string $permission) => Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]));
    }
}
