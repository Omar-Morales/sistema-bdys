<?php

namespace Tests\Feature;

use App\Models\Almacen;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CierreAlmacenControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Role $supervisorRole;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permission = Permission::firstOrCreate([
            'name' => 'view cierres',
            'guard_name' => 'web',
        ]);

        $this->supervisorRole = Role::firstOrCreate([
            'name' => 'Supervisor',
            'guard_name' => 'web',
        ]);

        $this->supervisorRole->syncPermissions([$permission]);
    }

    protected function actingAsSupervisor(): User
    {
        $user = User::factory()->create();
        $user->assignRole($this->supervisorRole);

        return $user;
    }

    public function test_index_returns_filtered_cierres_with_normalized_fecha(): void
    {
        $almacen = Almacen::factory()->create();
        $otherAlmacen = Almacen::factory()->create();

        $targetDate = Carbon::create(2024, 5, 1);

        Pedido::factory()->create([
            'almacen_id' => $almacen->id,
            'created_at' => $targetDate->copy()->setTime(10, 30),
            'monto_total' => 150,
            'monto_pagado' => 100,
            'saldo_pendiente' => 50,
        ]);

        Pedido::factory()->create([
            'almacen_id' => $almacen->id,
            'created_at' => $targetDate->copy()->setTime(15, 45),
            'monto_total' => 50,
            'monto_pagado' => 25,
            'saldo_pendiente' => 25,
        ]);

        Pedido::factory()->create([
            'almacen_id' => $otherAlmacen->id,
            'created_at' => $targetDate->copy()->addDay()->setTime(9, 0),
        ]);

        $user = $this->actingAsSupervisor();

        $response = $this->actingAs($user)->get(route('supervisor.cierres.index', [
            'fecha' => $targetDate->toDateString(),
            'almacen_id' => $almacen->id,
        ]));

        $response->assertOk();

        $response->assertViewHas('fecha', function ($fecha) use ($targetDate) {
            return $fecha instanceof Carbon && $fecha->isSameDay($targetDate);
        });

        $response->assertViewHas('almacenId', $almacen->id);

        $cierres = $response->viewData('cierres');

        $this->assertCount(1, $cierres);

        $cierre = $cierres->first();

        $this->assertInstanceOf(Carbon::class, $cierre->fecha);
        $this->assertTrue($cierre->fecha->isSameDay($targetDate));
        $this->assertEquals(200.0, (float) $cierre->total_monto);
        $this->assertEquals(125.0, (float) $cierre->total_pagado);
        $this->assertEquals(75.0, (float) $cierre->total_pendiente);
    }

    public function test_index_rejects_invalid_fecha(): void
    {
        $user = $this->actingAsSupervisor();

        $indexRoute = route('supervisor.cierres.index');

        $response = $this->actingAs($user)
            ->from($indexRoute)
            ->get(route('supervisor.cierres.index', ['fecha' => '2024-02-30']));

        $response->assertRedirect($indexRoute);
        $response->assertSessionHasErrors('fecha');
    }

    public function test_index_rejects_invalid_almacen_id(): void
    {
        $user = $this->actingAsSupervisor();

        $indexRoute = route('supervisor.cierres.index');

        $response = $this->actingAs($user)
            ->from($indexRoute)
            ->get(route('supervisor.cierres.index', ['almacen_id' => 9999]));

        $response->assertRedirect($indexRoute);
        $response->assertSessionHasErrors('almacen_id');
    }
}
