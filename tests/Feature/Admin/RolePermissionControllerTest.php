<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RolePermissionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_supervisor_keeps_manage_permissions_after_updating_roles(): void
    {
        $this->seed(PermissionsSeeder::class);
        $this->seed(RolesSeeder::class);

        /** @var Role $supervisorRole */
        $supervisorRole = Role::where('name', 'Supervisor')->firstOrFail();
        $supervisor = User::factory()->create();
        $supervisor->assignRole($supervisorRole);

        $viewPermissions = collect(config('modules.menus'))
            ->map(fn (array $module) => $module['permissions']['view'] ?? null)
            ->filter()
            ->values()
            ->all();

        $response = $this->actingAs($supervisor)->put(route('admin.roles.permissions.update'), [
            'roles' => [
                $supervisorRole->id => $viewPermissions,
            ],
        ]);

        $response->assertRedirect(route('admin.roles.permissions.index'));

        $supervisorRole->refresh();

        foreach (PermissionsSeeder::CRUD_MODULES as $module) {
            $this->assertTrue(
                $supervisorRole->hasPermissionTo('manage '.$module),
                sprintf('Failed asserting that the supervisor keeps the manage %s permission.', $module)
            );
        }
    }
}
