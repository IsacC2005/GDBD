<?php

namespace App\Filament\Resources\Compras\Schemas;

use App\Models\Producto;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('proveedor_id')
                    ->relationship('proveedor', 'nombre_empresa')
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('fecha_movimiento')
                    ->default(now())
                    ->required(),
                Textarea::make('motivo')
                    ->default('Compra registrada a proveedor')
                    ->columnSpanFull(),
                Repeater::make('items')
                    ->label('Productos')
                    ->schema([
                        Select::make('producto_id')
                            ->label('Producto')
                            ->options(fn (): array => Producto::query()->orderBy('nombre')->pluck('nombre', 'id')->all())
                            ->searchable()
                            ->required(),
                        TextInput::make('cantidad')
                            ->numeric()
                            ->minValue(0.01)
                            ->required(),
                        TextInput::make('precio')
                            ->label('Precio compra')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        TextInput::make('precio_balance')
                            ->label('Costo unitario')
                            ->helperText('Si se deja vacío, se usa el precio compra.')
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->columns(4)
                    ->minItems(1)
                    ->columnSpanFull(),
            ]);
    }
}
