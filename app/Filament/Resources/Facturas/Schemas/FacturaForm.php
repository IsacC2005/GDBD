<?php

namespace App\Filament\Resources\Facturas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FacturaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('movimiento_id')
                    ->relationship('movimiento', 'id')
                    ->required(),
                Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->required(),
                TextInput::make('numero_factura')
                    ->required(),
                DateTimePicker::make('fecha_emicion')
                    ->required(),
                TextInput::make('metodo_pago')
                    ->required(),
                TextInput::make('estado')
                    ->required(),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('impuestos')
                    ->required()
                    ->numeric(),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
            ]);
    }
}
