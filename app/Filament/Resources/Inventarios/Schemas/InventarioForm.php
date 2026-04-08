<?php

namespace App\Filament\Resources\Inventarios\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('producto_id')
                    ->relationship('producto', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('proveedor_id')
                    ->relationship('proveedor', 'nombre_empresa')
                    ->searchable()
                    ->preload(),
                TextInput::make('precio')
                    ->required()
                    ->numeric(),
                TextInput::make('precio_balance')
                    ->required()
                    ->numeric(),
                TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                Select::make('tipo_movimiento')
                    ->options([
                        'entrada' => 'entrada',
                        'salida' => 'salida',
                        'ajuste' => 'ajuste',
                    ])
                    ->required(),
                DateTimePicker::make('fecha_movimiento')
                    ->required(),
                Textarea::make('motivo')
                    ->columnSpanFull(),
            ]);
    }
}
