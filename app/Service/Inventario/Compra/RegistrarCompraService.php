<?php

namespace App\Service\Inventario\Compra;

use App\Models\Inventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistrarCompraService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function ejecutar(array $data): Inventario
    {
        /** @var array<int, array<string, mixed>> $items */
        $items = $data['items'] ?? [];

        if ($items === []) {
            throw ValidationException::withMessages([
                'items' => 'Debe agregar al menos un producto en la compra.',
            ]);
        }

        return DB::transaction(function () use ($data, $items): Inventario {
            $primerMovimiento = null;

            foreach ($items as $item) {
                $precioCompra = (float) ($item['precio'] ?? 0);

                $movimiento = Inventario::create([
                    'producto_id' => (int) $item['producto_id'],
                    'proveedor_id' => (int) $data['proveedor_id'],
                    'precio' => $precioCompra,
                    'precio_balance' => (float) ($item['precio_balance'] ?? $precioCompra),
                    'cantidad' => (float) $item['cantidad'],
                    'tipo_movimiento' => 'entrada',
                    'fecha_movimiento' => $data['fecha_movimiento'],
                    'motivo' => $data['motivo'] ?? 'Compra registrada a proveedor',
                ]);

                $primerMovimiento ??= $movimiento;
            }

            if (! $primerMovimiento instanceof Inventario) {
                throw ValidationException::withMessages([
                    'items' => 'No se pudo registrar la compra.',
                ]);
            }

            return $primerMovimiento;
        });
    }
}
