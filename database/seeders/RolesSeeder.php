<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $modules = collect(config('modules.menus'));

        $viewPermissions = $modules
            ->map(fn (array $module, string $key) => $module['permissions']['view'] ?? $module['permission'] ?? 'view '.$key);

        $managePermissions = $modules
            ->map(fn (array $module) => $module['permissions']['manage'] ?? null)
            ->filter()
            ->values();

        $supervisorPermissions = $viewPermissions
            ->merge($managePermissions)
            ->unique()
            ->values()
            ->all();

        $supervisor = Role::firstOrCreate([
            'name' => 'Supervisor',
            'guard_name' => 'web',
        ]);
        $supervisor->syncPermissions($supervisorPermissions);

        $encargadoModules = collect(['dashboard', 'productos', 'pedidos', 'almacen_pedidos']);

        $encargadoPermissions = $encargadoModules
            ->filter(fn (string $module) => $modules->has($module))
            ->map(fn (string $module) => Arr::get($modules[$module]['permissions'] ?? [], 'view', 'view '.$module))
            ->values()
            ->all();

        $encargado = Role::firstOrCreate([
            'name' => 'Encargado',
            'guard_name' => 'web',
        ]);
        $encargado->syncPermissions($encargadoPermissions);
    }
}
