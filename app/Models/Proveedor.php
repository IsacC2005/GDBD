<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre_empresa',
        'nit_cedula',
        'telefono',
        'direccion',
        'correo',
        'nombre_contacto',
    ];

    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class);
    }
}
