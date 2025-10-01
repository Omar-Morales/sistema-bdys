<?php

namespace Database\Factories;

use App\Models\Almacen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Almacen>
 */
class AlmacenFactory extends Factory
{
    protected $model = Almacen::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->randomElement(['Curva', 'Milla', 'Santa Carolina', $this->faker->company()]),
            'direccion' => $this->faker->address(),
        ];
    }
}
