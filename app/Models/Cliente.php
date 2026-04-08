<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

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
