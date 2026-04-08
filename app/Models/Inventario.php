<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Inventario extends Model
{
    protected $fillable = [
        'producto_id',
        'proveedor_id',
        'precio',
        'precio_balance',
        'cantidad',
        'tipo_movimiento',
        'fecha_movimiento',
        'motivo',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'float',
            'precio_balance' => 'float',
            'cantidad' => 'float',
            'fecha_movimiento' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Inventario $movimiento): void {
            $movimiento->aplicarStockEnCreacion();
        });

        static::updating(function (Inventario $movimiento): void {
            $movimiento->aplicarStockEnActualizacion();
        });

        static::deleting(function (Inventario $movimiento): void {
            $movimiento->aplicarStockEnEliminacion();
        });
    }

    private function aplicarStockEnCreacion(): void
    {
        DB::transaction(function (): void {
            $producto = Producto::query()->lockForUpdate()->findOrFail($this->producto_id);

            $nuevoStock = (float) $producto->stock + $this->cantidadFirmada();

            self::validarStock($producto, $nuevoStock);

            $producto->stock = $nuevoStock;
            $producto->save();
        });
    }

    private function aplicarStockEnActualizacion(): void
    {
        DB::transaction(function (): void {
            $productoOriginalId = (int) $this->getOriginal('producto_id');
            $productoNuevoId = (int) $this->producto_id;

            $cantidadOriginal = $this->cantidadFirmada(
                (string) $this->getOriginal('tipo_movimiento'),
                (float) $this->getOriginal('cantidad'),
            );
            $cantidadNueva = $this->cantidadFirmada();

            if ($productoOriginalId === $productoNuevoId) {
                $producto = Producto::query()->lockForUpdate()->findOrFail($productoNuevoId);

                $nuevoStock = (float) $producto->stock - $cantidadOriginal + $cantidadNueva;

                self::validarStock($producto, $nuevoStock);

                $producto->stock = $nuevoStock;
                $producto->save();

                return;
            }

            $ids = [$productoOriginalId, $productoNuevoId];
            sort($ids);

            $productos = Producto::query()
                ->whereIn('id', $ids)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $productoOriginal = $productos->get($productoOriginalId);
            $productoNuevo = $productos->get($productoNuevoId);

            if (! $productoOriginal || ! $productoNuevo) {
                throw ValidationException::withMessages([
                    'producto_id' => 'El producto seleccionado no existe.',
                ]);
            }

            $stockProductoOriginal = (float) $productoOriginal->stock - $cantidadOriginal;
            $stockProductoNuevo = (float) $productoNuevo->stock + $cantidadNueva;

            self::validarStock($productoOriginal, $stockProductoOriginal);
            self::validarStock($productoNuevo, $stockProductoNuevo);

            $productoOriginal->stock = $stockProductoOriginal;
            $productoNuevo->stock = $stockProductoNuevo;

            $productoOriginal->save();
            $productoNuevo->save();
        });
    }

    private function aplicarStockEnEliminacion(): void
    {
        DB::transaction(function (): void {
            $producto = Producto::query()->lockForUpdate()->findOrFail($this->producto_id);

            $nuevoStock = (float) $producto->stock - $this->cantidadFirmada();

            self::validarStock($producto, $nuevoStock);

            $producto->stock = $nuevoStock;
            $producto->save();
        });
    }

    private function cantidadFirmada(?string $tipoMovimiento = null, ?float $cantidad = null): float
    {
        $tipoMovimiento ??= $this->tipo_movimiento;
        $cantidad ??= (float) $this->cantidad;

        return match ($tipoMovimiento) {
            'entrada' => $cantidad,
            'salida' => -$cantidad,
            'ajuste' => $cantidad,
            default => 0,
        };
    }

    private static function validarStock(Producto $producto, float $stockCalculado): void
    {
        if ($stockCalculado < 0) {
            throw ValidationException::withMessages([
                'cantidad' => 'No se puede vender una cantidad mayor al stock disponible.',
            ]);
        }

        if ($producto->stock_maximo !== null && $stockCalculado > (float) $producto->stock_maximo) {
            throw ValidationException::withMessages([
                'cantidad' => 'El movimiento supera el stock maximo configurado para el producto.',
            ]);
        }
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'movimiento_id');
    }
}
