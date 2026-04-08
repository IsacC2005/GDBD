<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('categoria_id')
                    ->relationship('categoria', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('nombre')
                    ->required(),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                TextInput::make('precio_venta')
                    ->required()
                    ->numeric(),
                TextInput::make('costo_promedio')
                    ->numeric(),
                TextInput::make('stock_minimo')
                    ->required()
                    ->numeric(),
                TextInput::make('stock_maximo')
                    ->numeric(),
                TextInput::make('stock')
                    ->disabled()
                    ->dehydrated(false)
                    ->numeric(),
                Toggle::make('state')
                    ->required(),
                SpatieMediaLibraryFileUpload::make('imagen')
                    ->collection('productos')
                    ->image()
                    ->required(),
            ]);
    }
}
