<?php

namespace App\Filament\Resources\Proveedors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProveedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_empresa')
                    ->required(),
                TextInput::make('nit_cedula')
                    ->required(),
                TextInput::make('telefono')
                    ->tel()
                    ->required(),
                TextInput::make('direccion')
                    ->required(),
                TextInput::make('correo')
                    ->required(),
                TextInput::make('nombre_contacto')
                    ->required(),
            ]);
    }
}
