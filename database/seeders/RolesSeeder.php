<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $modules = config('modules.menus');
        $moduleKeys = array_keys($modules);

        $viewPermissions = collect($moduleKeys)
            ->map(fn (string $module) => 'view '.$module);

        $managePermissions = collect(PermissionsSeeder::CRUD_MODULES)
            ->filter(fn (string $module) => in_array($module, $moduleKeys, true))
            ->map(fn (string $module) => 'manage '.$module);

        $supervisorPermissions = $viewPermissions
            ->merge($managePermissions)
            ->all();

        $supervisor = Role::firstOrCreate([
            'name' => 'Supervisor',
            'guard_name' => 'web',
        ]);
        $supervisor->syncPermissions($supervisorPermissions);

        $encargadoPermissions = collect(['dashboard', 'productos', 'pedidos', 'cobros'])
            ->filter(fn (string $module) => array_key_exists($module, $modules))
            ->map(fn (string $module) => 'view '.$module)
            ->all();

        $encargado = Role::firstOrCreate([
            'name' => 'Encargado',
            'guard_name' => 'web',
        ]);
        $encargado->syncPermissions($encargadoPermissions);
    }
}
