<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Inventario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Factura>
 */
class FacturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 5000);
        $impuestos = fake()->randomFloat(2, 0, $subtotal * 0.19);

        return [
            'movimiento_id' => Inventario::factory(),
            'cliente_id' => Cliente::factory(),
            'numero_factura' => fake()->unique()->bothify('FAC-########-??'),
            'fecha_emicion' => fake()->dateTimeBetween('-30 years', 'now'),
            'metodo_pago' => fake()->randomElement(['Efectivo', 'Tarjeta', 'Transferencia']),
            'estado' => fake()->randomElement(['Pagada', 'Pendiente', 'Anulada']),
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'total' => $subtotal + $impuestos,
        ];
    }
}
