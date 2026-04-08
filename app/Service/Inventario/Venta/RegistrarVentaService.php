<?php

namespace App\Service\Inventario\Venta;

use App\Models\Factura;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegistrarVentaService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function ejecutar(array $data): Factura
    {
        /** @var array<int, array<string, mixed>> $items */
        $items = $data['items'] ?? [];

        if ($items === []) {
            throw ValidationException::withMessages([
                'items' => 'Debe agregar al menos un producto en la venta.',
            ]);
        }

        return DB::transaction(function () use ($data, $items): Factura {
            $this->validarStockDisponible($items);

            $primeraFactura = null;
            $totalItems = count($items);

            foreach ($items as $indice => $item) {
                $producto = Producto::query()->lockForUpdate()->findOrFail((int) $item['producto_id']);

                $cantidad = (float) $item['cantidad'];
                $precioUnitario = (float) $producto->precio_venta;
                $subtotal = $precioUnitario * $cantidad;
                $impuestos = (float) ($item['impuestos'] ?? 0);
                $total = $subtotal + $impuestos;

                $movimiento = Inventario::create([
                    'producto_id' => $producto->id,
                    'proveedor_id' => null,
                    'precio' => $precioUnitario,
                    'precio_balance' => (float) ($producto->costo_promedio ?? $precioUnitario),
                    'cantidad' => $cantidad,
                    'tipo_movimiento' => 'salida',
                    'fecha_movimiento' => $data['fecha_emicion'],
                    'motivo' => $data['motivo'] ?? 'Venta registrada desde panel de ventas',
                ]);

                $factura = Factura::create([
                    'movimiento_id' => $movimiento->id,
                    'cliente_id' => (int) $data['cliente_id'],
                    'numero_factura' => $this->generarNumeroFactura(
                        numeroBase: (string) ($data['numero_factura'] ?? ''),
                        indice: $indice,
                        totalItems: $totalItems,
                    ),
                    'fecha_emicion' => $data['fecha_emicion'],
                    'metodo_pago' => $data['metodo_pago'],
                    'estado' => $data['estado'],
                    'subtotal' => $subtotal,
                    'impuestos' => $impuestos,
                    'total' => $total,
                ]);

                $primeraFactura ??= $factura;
            }

            if (! $primeraFactura instanceof Factura) {
                throw ValidationException::withMessages([
                    'items' => 'No se pudo registrar la venta.',
                ]);
            }

            return $primeraFactura;
        });
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function validarStockDisponible(array $items): void
    {
        $cantidadesPorProducto = [];

        foreach ($items as $item) {
            $productoId = (int) ($item['producto_id'] ?? 0);
            $cantidad = (float) ($item['cantidad'] ?? 0);

            if ($productoId <= 0 || $cantidad <= 0) {
                continue;
            }

            $cantidadesPorProducto[$productoId] = ($cantidadesPorProducto[$productoId] ?? 0) + $cantidad;
        }

        $productos = Producto::query()
            ->whereIn('id', array_keys($cantidadesPorProducto))
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($cantidadesPorProducto as $productoId => $cantidadTotal) {
            /** @var Producto|null $producto */
            $producto = $productos->get($productoId);

            if (! $producto) {
                throw ValidationException::withMessages([
                    'items' => 'Uno de los productos seleccionados no existe.',
                ]);
            }

            if ((float) $producto->stock < (float) $cantidadTotal) {
                throw ValidationException::withMessages([
                    'items' => 'Se intenta vender '.(float) $cantidadTotal.' del producto '.$producto->nombre.' y solo hay '.(float) $producto->stock.' disponible(s).',
                ]);
            }
        }
    }

    private function generarNumeroFactura(string $numeroBase, int $indice, int $totalItems): string
    {
        $numeroBase = trim($numeroBase);

        if ($numeroBase !== '') {
            if ($totalItems === 1) {
                return $numeroBase;
            }

            return $numeroBase.'-'.str_pad((string) ($indice + 1), 2, '0', STR_PAD_LEFT);
        }

        return 'FAC-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(4)).'-'.str_pad((string) ($indice + 1), 2, '0', STR_PAD_LEFT);
    }
}
