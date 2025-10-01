<?php

namespace Database\Factories;

use App\Models\Cobro;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Cobro>
 */
class CobroFactory extends Factory
{
    protected $model = Cobro::class;

    public function definition(): array
    {
        $monto = $this->faker->randomFloat(2, 50, 500);
        $montoPagado = $this->faker->randomFloat(2, 0, $monto);
        $tipoPago = $montoPagado > 0 ? $this->faker->randomElement(Cobro::TIPOS_PAGO) : null;
        $estadoPago = $montoPagado >= $monto
            ? Cobro::ESTADO_PAGO_PAGADO
            : ($montoPagado > 0 ? Cobro::ESTADO_PAGO_POR_COBRAR : Cobro::ESTADO_PAGO_PENDIENTE);

        return [
            'pedido_id' => Pedido::factory(),
            'monto' => $monto,
            'monto_pagado' => $montoPagado,
            'tipo_pago' => $tipoPago,
            'estado_pago' => $estadoPago,
            'metodo' => $tipoPago ?? Cobro::TIPO_PAGO_EFECTIVO,
            'registrado_por' => User::factory(),
            'observaciones' => $this->faker->optional()->sentence(),
        ];
    }
}
