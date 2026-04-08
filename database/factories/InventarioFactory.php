<?php

namespace Database\Factories;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inventario>
 */
class InventarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipoMovimiento = fake()->randomElement(['entrada', 'salida', 'ajuste']);

        return [
            'producto_id' => Producto::factory(),
            'proveedor_id' => $tipoMovimiento === 'entrada' ? Proveedor::factory() : null,
            'precio' => fake()->randomFloat(2, 5, 1500),
            'precio_balance' => fake()->randomFloat(2, 5, 1200),
            'cantidad' => fake()->randomFloat(2, 1, 80),
            'tipo_movimiento' => $tipoMovimiento,
            'fecha_movimiento' => fake()->dateTimeBetween('-30 years', 'now'),
            'motivo' => fake()->optional(0.7)->sentence(),
        ];
    }
}
