<?php

namespace Database\Factories;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\DetallePedido>
 */
class DetallePedidoFactory extends Factory
{
    protected $model = DetallePedido::class;

    public function definition(): array
    {
        $cantidad = $this->faker->randomFloat(2, 1, 100);
        $precioUnitario = $this->faker->randomFloat(2, 5, 200);

        return [
            'pedido_id' => Pedido::factory(),
            'producto_id' => Producto::factory(),
            'cantidad' => $cantidad,
            'unidad' => $this->faker->randomElement(DetallePedido::UNIDADES),
            'metraje' => $this->faker->optional()->randomFloat(2, 1, 80),
            'precio_unitario' => $precioUnitario,
            'precio_final' => $precioUnitario,
            'subtotal' => round($cantidad * $precioUnitario, 2),
        ];
    }
}
