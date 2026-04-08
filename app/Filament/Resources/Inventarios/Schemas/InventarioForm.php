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
                    ->relationship('producto', 'id')
                    ->required(),
                Select::make('proveedor_id')
                    ->relationship('proveedor', 'id'),
                TextInput::make('precio')
                    ->required()
                    ->numeric(),
                TextInput::make('precio_balance')
                    ->required()
                    ->numeric(),
                TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                TextInput::make('tipo_movimiento')
                    ->required(),
                DateTimePicker::make('fecha_movimiento')
                    ->required(),
                Textarea::make('motivo')
                    ->columnSpanFull(),
            ]);
    }
}
