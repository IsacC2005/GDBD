<?php

namespace Database\Factories;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proveedor>
 */
class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_empresa' => fake()->unique()->company(),
            'nit_cedula' => fake()->unique()->numerify('##########'),
            'telefono' => fake()->numerify('3#########'),
            'direccion' => fake()->address(),
            'correo' => fake()->unique()->companyEmail(),
            'nombre_contacto' => fake()->name(),
        ];
    }
}
