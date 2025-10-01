<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $modules = config('modules.menus');
        $allPermissions = Permission::pluck('name')->all();

        $supervisor = Role::firstOrCreate([
            'name' => 'Supervisor',
            'guard_name' => 'web',
        ]);
        $supervisor->syncPermissions($allPermissions);

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
