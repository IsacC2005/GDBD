<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory;
    use SoftDeletes;

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
