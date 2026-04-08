<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

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
