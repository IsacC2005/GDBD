<?php

namespace App\Filament\Resources\Compras\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComprasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('proveedor.nombre_empresa')
                    ->label('Proveedor')
                    ->searchable(),
                TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->searchable(),
                TextColumn::make('cantidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('precio')
                    ->label('Precio compra')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('precio_balance')
                    ->label('Costo unitario')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fecha_movimiento')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('motivo')
                    ->limit(40),
            ])
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
