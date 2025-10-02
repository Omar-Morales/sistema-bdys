<?php

namespace Tests\Feature;

use App\Models\Almacen;
use App\Models\Cobro;
use App\Models\Pedido;
use App\Models\Tienda;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function ensureDashboardPermission(): void
    {
        Permission::firstOrCreate(['name' => 'view dashboard', 'guard_name' => 'web']);
    }

    public function test_supervisor_receives_global_metrics(): void
    {
        $this->ensureDashboardPermission();

        $supervisorRole = Role::firstOrCreate(['name' => 'Supervisor', 'guard_name' => 'web']);
        $supervisorRole->givePermissionTo('view dashboard');

        $supervisor = User::factory()->create();
        $supervisor->assignRole($supervisorRole);

        $almacenNorte = Almacen::factory()->create(['nombre' => 'Norte']);
        $almacenSur = Almacen::factory()->create(['nombre' => 'Sur']);
        $tienda = Tienda::factory()->create();
        $vendedor = Vendedor::factory()->create(['tienda_id' => $tienda->id]);
        $encargadoNorte = User::factory()->create(['almacen_id' => $almacenNorte->id]);
        $encargadoSur = User::factory()->create(['almacen_id' => $almacenSur->id]);

        $pedidoNorte = Pedido::factory()
            ->for($tienda)
            ->for($vendedor)
            ->for($almacenNorte, 'almacen')
            ->for($encargadoNorte, 'encargado')
            ->create([
                'monto_total' => 1000,
                'monto_pagado' => 400,
                'saldo_pendiente' => 600,
                'estado_pedido' => Pedido::ESTADO_PEDIDO_EN_CURSO,
            ]);

        $pedidoSur = Pedido::factory()
            ->for($tienda)
            ->for($vendedor)
            ->for($almacenSur, 'almacen')
            ->for($encargadoSur, 'encargado')
            ->create([
                'monto_total' => 500,
                'monto_pagado' => 500,
                'saldo_pendiente' => 0,
                'estado_pedido' => Pedido::ESTADO_PEDIDO_ENTREGADO,
            ]);

        Cobro::factory()->for($pedidoNorte)
            ->create([
                'monto' => 400,
                'monto_pagado' => 200,
                'estado_pago' => Cobro::ESTADO_PAGO_POR_COBRAR,
                'tipo_pago' => Cobro::TIPO_PAGO_EFECTIVO,
                'metodo' => Cobro::TIPO_PAGO_EFECTIVO,
                'registrado_por' => $encargadoNorte->id,
            ]);

        Cobro::factory()->for($pedidoSur)
            ->create([
                'monto' => 500,
                'monto_pagado' => 500,
                'estado_pago' => Cobro::ESTADO_PAGO_PAGADO,
                'tipo_pago' => Cobro::TIPO_PAGO_EFECTIVO,
                'metodo' => Cobro::TIPO_PAGO_EFECTIVO,
                'registrado_por' => $encargadoSur->id,
            ]);

        $response = $this->actingAs($supervisor)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('mostrandoGlobal', true);
        $response->assertViewHas('totalesGenerales', function (array $totales): bool {
            return $totales['total_pedidos'] === 2
                && abs($totales['monto_total'] - 1500) < 0.01
                && abs($totales['monto_pagado'] - 900) < 0.01;
        });
        $response->assertViewHas('metricasPorAlmacen', function ($metricas) use ($almacenNorte, $almacenSur) {
            return $metricas->count() === 2
                && $metricas->contains(fn ($row) => $row['almacen_id'] === $almacenNorte->id && abs($row['monto_total'] - 1000) < 0.01)
                && $metricas->contains(fn ($row) => $row['almacen_id'] === $almacenSur->id && abs($row['monto_pagado'] - 500) < 0.01);
        });
    }

    public function test_encargado_only_sees_metrics_for_their_warehouse(): void
    {
        $this->ensureDashboardPermission();

        $encargadoRole = Role::firstOrCreate(['name' => 'Encargado', 'guard_name' => 'web']);
        $encargadoRole->givePermissionTo('view dashboard');

        $almacenCentral = Almacen::factory()->create(['nombre' => 'Central']);
        $almacenExterno = Almacen::factory()->create(['nombre' => 'Externo']);
        $tienda = Tienda::factory()->create();
        $vendedor = Vendedor::factory()->create(['tienda_id' => $tienda->id]);

        $encargado = User::factory()->create(['almacen_id' => $almacenCentral->id]);
        $encargado->assignRole($encargadoRole);

        $pedidoCentral = Pedido::factory()
            ->for($tienda)
            ->for($vendedor)
            ->for($almacenCentral, 'almacen')
            ->for($encargado, 'encargado')
            ->create([
                'monto_total' => 800,
                'monto_pagado' => 300,
                'saldo_pendiente' => 500,
                'estado_pedido' => Pedido::ESTADO_PEDIDO_EN_CURSO,
            ]);

        Pedido::factory()
            ->for($tienda)
            ->for($vendedor)
            ->for($almacenExterno, 'almacen')
            ->for(User::factory()->create(['almacen_id' => $almacenExterno->id]), 'encargado')
            ->create([
                'monto_total' => 1200,
                'monto_pagado' => 1200,
                'saldo_pendiente' => 0,
                'estado_pedido' => Pedido::ESTADO_PEDIDO_ENTREGADO,
            ]);

        Cobro::factory()->for($pedidoCentral)
            ->create([
                'monto' => 300,
                'monto_pagado' => 100,
                'estado_pago' => Cobro::ESTADO_PAGO_POR_COBRAR,
                'tipo_pago' => Cobro::TIPO_PAGO_EFECTIVO,
                'metodo' => Cobro::TIPO_PAGO_EFECTIVO,
                'registrado_por' => $encargado->id,
            ]);

        $response = $this->actingAs($encargado)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('mostrandoGlobal', false);
        $response->assertViewHas('totalesGenerales', function (array $totales): bool {
            return $totales['total_pedidos'] === 1
                && abs($totales['monto_total'] - 800) < 0.01
                && abs($totales['monto_pagado'] - 300) < 0.01;
        });
        $response->assertViewHas('metricasPorAlmacen', function ($metricas) use ($almacenCentral, $almacenExterno) {
            return $metricas->count() === 1
                && $metricas->first()['almacen_id'] === $almacenCentral->id
                && $metricas->doesntContain(fn ($row) => $row['almacen_id'] === $almacenExterno->id);
        });
    }
}
