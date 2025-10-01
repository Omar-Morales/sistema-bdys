<?php

namespace Tests\Feature;

use App\Models\Almacen;
use App\Models\Pedido;
use App\Models\Tienda;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ModuleVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_supervisor_sees_assigned_modules(): void
    {
        $permissions = [
            'view dashboard',
            'view pedidos',
            'manage pedidos',
            'view cierres',
            'view admin permisos',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $role = Role::firstOrCreate(['name' => 'Supervisor', 'guard_name' => 'web']);
        $role->givePermissionTo($permissions);

        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Pedidos', false);
        $response->assertSee('Cierre Diario', false);
        $response->assertSee('Permisos', false);
        $response->assertDontSee('Pedidos de Almacén');
    }

    public function test_encargado_only_sees_assigned_modules(): void
    {
        $permissions = [
            'view dashboard',
            'view pedidos almacenes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $role = Role::firstOrCreate(['name' => 'Encargado', 'guard_name' => 'web']);
        $role->givePermissionTo($permissions);

        $almacen = Almacen::factory()->create();

        $user = User::factory()->create(['almacen_id' => $almacen->id]);
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Pedidos de Almacén', false);
        $response->assertDontSee('/supervisor/pedidos');
    }

    public function test_encargado_sees_only_pedidos_for_assigned_almacen(): void
    {
        Permission::firstOrCreate(['name' => 'view pedidos almacenes', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view dashboard', 'guard_name' => 'web']);

        $role = Role::firstOrCreate(['name' => 'Encargado', 'guard_name' => 'web']);
        $role->givePermissionTo(['view pedidos almacenes', 'view dashboard']);

        $almacenUno = Almacen::factory()->create(['nombre' => 'Almacén 1']);
        $almacenDos = Almacen::factory()->create(['nombre' => 'Almacén 2']);

        $tienda = Tienda::factory()->create();
        $vendedor = Vendedor::factory()->create(['tienda_id' => $tienda->id]);
        $encargado = User::factory()->create(['almacen_id' => $almacenUno->id]);
        $encargado->assignRole($role);

        $pedidoAsignado = Pedido::factory()->create([
            'tienda_id' => $tienda->id,
            'vendedor_id' => $vendedor->id,
            'almacen_id' => $almacenUno->id,
            'almacen_destino_id' => $almacenUno->id,
            'encargado_id' => $encargado->id,
        ]);

        $pedidoExterno = Pedido::factory()->create([
            'tienda_id' => $tienda->id,
            'vendedor_id' => $vendedor->id,
            'almacen_id' => $almacenDos->id,
            'almacen_destino_id' => $almacenDos->id,
            'encargado_id' => $encargado->id,
        ]);

        $response = $this->actingAs($encargado)->get(route('almacen.pedidos.index'));

        $response->assertOk();
        $response->assertSee('#'.$pedidoAsignado->id, false);
        $response->assertDontSee('#'.$pedidoExterno->id, false);
    }
}
