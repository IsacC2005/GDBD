<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $precioVenta = fake()->randomFloat(2, 5, 1500);

        return [
            'categoria_id' => Categoria::factory(),
            'sku' => fake()->unique()->bothify('SKU-####-????'),
            'nombre' => fake()->unique()->words(3, true),
            'descripcion' => fake()->optional(0.7)->sentence(),
            'precio_venta' => $precioVenta,
            'costo_promedio' => fake()->randomFloat(2, 3, $precioVenta),
            'stock' => 0,
            'stock_minimo' => fake()->randomFloat(2, 5, 50),
            'stock_maximo' => fake()->randomFloat(2, 500, 5000),
            'state' => fake()->boolean(95),
        ];
    }
}
