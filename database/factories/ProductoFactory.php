<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\DetallePedido;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'categoria_id' => Categoria::factory(),
            'nombre' => $this->faker->unique()->randomElement([
                'Bloque de concreto 20x40',
                'Cemento Portland',
                'Varilla corrugada 3/8',
                'Pintura látex interior',
            ]),
            'medida' => $this->faker->randomElement(['25kg', '50kg', '1gl', 'unid']),
            'unidad' => $this->faker->randomElement(DetallePedido::UNIDADES),
            'piezas_por_caja' => $this->faker->numberBetween(1, 20),
            'precio_referencial' => $this->faker->randomFloat(2, 10, 250),
        ];
    }
}
