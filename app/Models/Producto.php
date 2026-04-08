<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Producto extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $attributes = [
        'stock' => 0,
    ];

    protected $fillable = [
        'categoria_id',
        'sku',
        'nombre',
        'descripcion',
        'precio_venta',
        'costo_promedio',
        'stock_minimo',
        'stock_maximo',
        'state',
    ];

    protected function casts(): array
    {
        return [
            'precio_venta' => 'float',
            'costo_promedio' => 'float',
            'stock' => 'float',
            'stock_minimo' => 'float',
            'stock_maximo' => 'float',
            'state' => 'boolean',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'categoria_producto');
    }

    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class);
    }
}
