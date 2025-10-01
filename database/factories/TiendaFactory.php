<?php

namespace Database\Factories;

use App\Models\Tienda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Tienda>
 */
class TiendaFactory extends Factory
{
    protected $model = Tienda::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company(),
            'sector' => $this->faker->randomElement(['Curva', 'Milla', 'Santa Carolina', 'Industrial', 'Residencial']),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
        ];
    }
}
