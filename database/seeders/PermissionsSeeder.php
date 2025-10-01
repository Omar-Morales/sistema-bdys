<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $modules = config('modules.menus');

        foreach ($modules as $key => $label) {
            Permission::firstOrCreate([
                'name' => 'view '.$key,
                'guard_name' => 'web',
            ]);
        }
    }
}
