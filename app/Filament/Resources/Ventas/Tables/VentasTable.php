<?php

namespace App\Filament\Resources\Ventas\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_factura')
                    ->searchable(),
                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('movimiento.producto.nombre')
                    ->label('Producto')
                    ->searchable(),
                TextColumn::make('movimiento.cantidad')
                    ->label('Cantidad')
                    ->numeric(),
                TextColumn::make('metodo_pago')
                    ->searchable(),
                TextColumn::make('estado')
                    ->searchable(),
                TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('impuestos')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fecha_emicion')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
