<?php

namespace Database\Factories;

use App\Models\Tienda;
use App\Models\Vendedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Vendedor>
 */
class VendedorFactory extends Factory
{
    protected $model = Vendedor::class;

    public function definition(): array
    {
        return [
            'tienda_id' => Tienda::factory(),
            'nombre' => $this->faker->name(),
            'telefono' => $this->faker->phoneNumber(),
        ];
    }
}
