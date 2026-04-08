<?php

namespace App\Filament\Resources\Compras\Pages;

use App\Filament\Resources\Compras\CompraResource;
use App\Models\Inventario;
use App\Models\Producto;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateCompra extends CreateRecord
{
    protected static string $resource = CompraResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Inventario {
            $producto = Producto::query()->lockForUpdate()->findOrFail($data['producto_id']);

            $precioCompra = (float) $data['precio'];

            return Inventario::create([
                'producto_id' => $producto->id,
                'proveedor_id' => $data['proveedor_id'],
                'precio' => $precioCompra,
                'precio_balance' => (float) ($data['precio_balance'] ?? $precioCompra),
                'cantidad' => (float) $data['cantidad'],
                'tipo_movimiento' => 'entrada',
                'fecha_movimiento' => $data['fecha_movimiento'],
                'motivo' => $data['motivo'] ?? 'Compra registrada a proveedor',
            ]);
        });
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Compra registrada correctamente');
    }
}
