<?php

namespace App\Filament\Resources\Ventas\Schemas;

use App\Models\Producto;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->relationship('cliente', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('producto_id')
                    ->label('Producto')
                    ->options(fn (): array => Producto::query()->orderBy('nombre')->pluck('nombre', 'id')->all())
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('cantidad')
                    ->numeric()
                    ->minValue(0.01)
                    ->required(),
                Select::make('metodo_pago')
                    ->options([
                        'Efectivo' => 'Efectivo',
                        'Tarjeta' => 'Tarjeta',
                        'Transferencia' => 'Transferencia',
                    ])
                    ->required(),
                Select::make('estado')
                    ->options([
                        'Pagada' => 'Pagada',
                        'Pendiente' => 'Pendiente',
                        'Anulada' => 'Anulada',
                    ])
                    ->default('Pagada')
                    ->required(),
                DateTimePicker::make('fecha_emicion')
                    ->default(now())
                    ->required(),
                TextInput::make('impuestos')
                    ->default(0)
                    ->numeric()
                    ->required(),
                TextInput::make('numero_factura')
                    ->maxLength(255),
                Textarea::make('motivo')
                    ->default('Venta registrada desde panel de ventas')
                    ->columnSpanFull(),
            ]);
    }
}
