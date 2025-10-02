<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\Categoria;
use App\Models\Cobro;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
        ]);

        $supervisorEmail = 'admin@bdys.com';

        $user = User::firstOrCreate(
            ['email' => $supervisorEmail],
            [
                'name' => 'Supervisor BDYS',
                'password' => Hash::make('12345678'),
            ]
        );

        if (!$user->hasRole('Supervisor')) {
            $user->assignRole('Supervisor');
        }

        $almacenes = collect([
            ['nombre' => 'Curva', 'direccion' => 'Av. La Curva 120 - Trujillo'],
            ['nombre' => 'Milla', 'direccion' => 'Jr. Milla 450 - Trujillo'],
            ['nombre' => 'Santa Carolina', 'direccion' => 'Av. Santa Carolina 900 - Trujillo'],
        ])->mapWithKeys(fn (array $almacen) => [
            $almacen['nombre'] => Almacen::updateOrCreate(
                ['nombre' => $almacen['nombre']],
                ['direccion' => $almacen['direccion']]
            ),
        ]);

        $tienda = Tienda::updateOrCreate(
            ['nombre' => 'Ferretería El Porvenir'],
            [
                'sector' => 'Curva',
                'direccion' => 'Av. Las Magnolias 315 - El Porvenir',
                'telefono' => '944-123-456',
            ]
        );

        $vendedor = Vendedor::updateOrCreate(
            [
                'tienda_id' => $tienda->id,
                'nombre' => 'María Torres',
            ],
            ['telefono' => '900-111-222']
        );

        $categoria = Categoria::firstOrCreate(['nombre' => 'Construcción']);

        $productos = [
            'ladrillo' => Producto::updateOrCreate(
                ['nombre' => 'Ladrillo King Kong 18H'],
                [
                    'categoria_id' => $categoria->id,
                    'medida' => 'caja 25 und',
                    'unidad' => DetallePedido::UNIDAD_CAJA,
                    'piezas_por_caja' => 25,
                    'precio_referencial' => 13.50,
                ]
            ),
            'cemento' => Producto::updateOrCreate(
                ['nombre' => 'Cemento Portland Tipo I'],
                [
                    'categoria_id' => $categoria->id,
                    'medida' => 'bolsa 42.5kg',
                    'unidad' => DetallePedido::UNIDAD_KG,
                    'piezas_por_caja' => 1,
                    'precio_referencial' => 32.80,
                ]
            ),
            'yeso' => Producto::updateOrCreate(
                ['nombre' => 'Yeso en polvo 25kg'],
                [
                    'categoria_id' => $categoria->id,
                    'medida' => 'saco 25kg',
                    'unidad' => DetallePedido::UNIDAD_KG,
                    'piezas_por_caja' => 1,
                    'precio_referencial' => 18.90,
                ]
            ),
        ];

        $pedidoParcial = Pedido::updateOrCreate(
            ['notas' => 'Pedido parcial con transferencia BCP'],
            [
                'tienda_id' => $tienda->id,
                'vendedor_id' => $vendedor->id,
                'almacen_id' => $almacenes['Curva']->id,
                'encargado_id' => $user->id,
                'monto_total' => 1200.00,
                'monto_pagado' => 700.00,
                'saldo_pendiente' => 500.00,
                'metraje_total' => 48.50,
                'cantidad_total' => 80,
                'unidad_referencia' => DetallePedido::UNIDAD_CAJA,
                'precio_promedio' => 15.00,
                'tipo_entrega' => Pedido::TIPO_ENTREGA_ENVIO_TIENDA,
                'tipo_pago' => Pedido::TIPO_PAGO_BCP,
                'estado_pago' => Pedido::ESTADO_PAGO_POR_COBRAR,
                'estado_pedido' => Pedido::ESTADO_PEDIDO_EN_CURSO,
                'cobra_almacen' => true,
            ]
        );

        $pedidoParcial->detalles()->delete();
        $pedidoParcial->cobros()->delete();

        $pedidoParcial->detalles()->createMany([
            [
                'producto_id' => $productos['ladrillo']->id,
                'cantidad' => 50,
                'unidad' => DetallePedido::UNIDAD_CAJA,
                'metraje' => 30.25,
                'precio_unitario' => 12.00,
                'precio_final' => 12.00,
                'subtotal' => 600.00,
            ],
            [
                'producto_id' => $productos['cemento']->id,
                'cantidad' => 30,
                'unidad' => DetallePedido::UNIDAD_KG,
                'metraje' => 18.25,
                'precio_unitario' => 20.00,
                'precio_final' => 20.00,
                'subtotal' => 600.00,
            ],
        ]);

        $pedidoParcial->cobros()->createMany([
            [
                'monto' => 400.00,
                'monto_pagado' => 400.00,
                'tipo_pago' => Cobro::TIPO_PAGO_BCP,
                'estado_pago' => Cobro::ESTADO_PAGO_PAGADO,
                'metodo' => Cobro::TIPO_PAGO_BCP,
                'registrado_por' => $user->id,
                'observaciones' => 'Transferencia BCP inicial',
            ],
            [
                'monto' => 300.00,
                'monto_pagado' => 300.00,
                'tipo_pago' => Cobro::TIPO_PAGO_YAPE,
                'estado_pago' => Cobro::ESTADO_PAGO_PAGADO,
                'metodo' => Cobro::TIPO_PAGO_YAPE,
                'registrado_por' => $user->id,
                'observaciones' => 'Pago adicional con Yape',
            ],
        ]);

        $pedidoCompleto = Pedido::updateOrCreate(
            ['notas' => 'Pedido entregado y cancelado en efectivo'],
            [
                'tienda_id' => $tienda->id,
                'vendedor_id' => $vendedor->id,
                'almacen_id' => $almacenes['Santa Carolina']->id,
                'encargado_id' => $user->id,
                'monto_total' => 850.00,
                'monto_pagado' => 850.00,
                'saldo_pendiente' => 0.00,
                'metraje_total' => 35.75,
                'cantidad_total' => 60,
                'unidad_referencia' => DetallePedido::UNIDAD_CAJA,
                'precio_promedio' => 14.17,
                'tipo_entrega' => Pedido::TIPO_ENTREGA_DELIVERY_CLIENTE,
                'tipo_pago' => Pedido::TIPO_PAGO_EFECTIVO,
                'estado_pago' => Pedido::ESTADO_PAGO_PAGADO,
                'estado_pedido' => Pedido::ESTADO_PEDIDO_ENTREGADO,
                'cobra_almacen' => false,
            ]
        );

        $pedidoCompleto->detalles()->delete();
        $pedidoCompleto->cobros()->delete();

        $pedidoCompleto->detalles()->createMany([
            [
                'producto_id' => $productos['ladrillo']->id,
                'cantidad' => 40,
                'unidad' => DetallePedido::UNIDAD_CAJA,
                'metraje' => 20.50,
                'precio_unitario' => 13.50,
                'precio_final' => 13.50,
                'subtotal' => 540.00,
            ],
            [
                'producto_id' => $productos['yeso']->id,
                'cantidad' => 20,
                'unidad' => DetallePedido::UNIDAD_KG,
                'metraje' => 15.25,
                'precio_unitario' => 15.50,
                'precio_final' => 15.50,
                'subtotal' => 310.00,
            ],
        ]);

        $pedidoCompleto->cobros()->create([
            'monto' => 850.00,
            'monto_pagado' => 850.00,
            'tipo_pago' => Cobro::TIPO_PAGO_EFECTIVO,
            'estado_pago' => Cobro::ESTADO_PAGO_PAGADO,
            'metodo' => Cobro::TIPO_PAGO_EFECTIVO,
            'registrado_por' => $user->id,
            'observaciones' => 'Cancelado en efectivo contra entrega',
        ]);
    }
}
