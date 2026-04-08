<?php

namespace App\Filament\Resources\Ventas\Pages;

use App\Filament\Resources\Ventas\VentaResource;
use App\Models\Factura;
use App\Models\Inventario;
use App\Models\Producto;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateVenta extends CreateRecord
{
    protected static string $resource = VentaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Factura {
            $producto = Producto::query()->lockForUpdate()->findOrFail($data['producto_id']);

            $cantidad = (float) $data['cantidad'];

            if ((float) $producto->stock < $cantidad) {
                throw ValidationException::withMessages([
                    'cantidad' => 'No se puede vender una cantidad mayor a la disponible en stock.',
                ]);
            }

            $precioUnitario = (float) $producto->precio_venta;
            $subtotal = $precioUnitario * $cantidad;
            $impuestos = (float) ($data['impuestos'] ?? 0);
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

            return Factura::create([
                'movimiento_id' => $movimiento->id,
                'cliente_id' => $data['cliente_id'],
                'numero_factura' => $data['numero_factura'] ?: $this->generarNumeroFactura(),
                'fecha_emicion' => $data['fecha_emicion'],
                'metodo_pago' => $data['metodo_pago'],
                'estado' => $data['estado'],
                'subtotal' => $subtotal,
                'impuestos' => $impuestos,
                'total' => $total,
            ]);
        });
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Venta registrada correctamente');
    }

    private function generarNumeroFactura(): string
    {
        do {
            $numero = 'FAC-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(4));
        } while (Factura::query()->where('numero_factura', $numero)->exists());

        return $numero;
    }
}
