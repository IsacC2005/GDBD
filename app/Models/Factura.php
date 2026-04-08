<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'movimiento_id',
        'cliente_id',
        'numero_factura',
        'fecha_emicion',
        'metodo_pago',
        'estado',
        'subtotal',
        'impuestos',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emicion' => 'datetime',
            'subtotal' => 'float',
            'impuestos' => 'float',
            'total' => 'float',
        ];
    }

    public function movimiento(): BelongsTo
    {
        return $this->belongsTo(Inventario::class, 'movimiento_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
