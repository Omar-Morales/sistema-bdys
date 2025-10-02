<?php

namespace Database\Factories;

use App\Models\Almacen;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Tienda;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition(): array
    {
        $montoTotal = $this->faker->randomFloat(2, 300, 2000);
        $montoPagado = $this->faker->randomFloat(2, 0, $montoTotal);
        $cantidadTotal = $this->faker->randomFloat(2, 5, 250);
        $metrajeTotal = $this->faker->randomFloat(2, 10, 150);
        $tipoPago = $montoPagado > 0 ? $this->faker->randomElement(Pedido::TIPOS_PAGO) : null;
        $estadoPago = $montoPagado >= $montoTotal
            ? Pedido::ESTADO_PAGO_PAGADO
            : ($montoPagado > 0 ? Pedido::ESTADO_PAGO_POR_COBRAR : Pedido::ESTADO_PAGO_PENDIENTE);

        return [
            'tienda_id' => Tienda::factory(),
            'vendedor_id' => Vendedor::factory(),
            'almacen_id' => Almacen::factory(),
            'encargado_id' => User::factory(),
            'monto_total' => $montoTotal,
            'monto_pagado' => $montoPagado,
            'saldo_pendiente' => max($montoTotal - $montoPagado, 0),
            'metraje_total' => $metrajeTotal,
            'cantidad_total' => $cantidadTotal,
            'unidad_referencia' => $this->faker->randomElement(DetallePedido::UNIDADES),
            'precio_promedio' => $cantidadTotal > 0 ? round($montoTotal / $cantidadTotal, 2) : $montoTotal,
            'tipo_entrega' => $this->faker->randomElement(Pedido::TIPOS_ENTREGA),
            'tipo_pago' => $tipoPago,
            'estado_pago' => $estadoPago,
            'estado_pedido' => $this->faker->randomElement(Pedido::ESTADOS_PEDIDO),
            'cobra_almacen' => $this->faker->boolean(),
            'notas' => $this->faker->optional()->sentence(),
        ];
    }
}
