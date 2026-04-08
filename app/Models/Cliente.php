<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'cedula',
        'nombre',
        'correo',
        'telefono',
    ];

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }
}
