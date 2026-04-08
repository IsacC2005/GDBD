<?php

namespace App\Filament\Resources\Ventas\Schemas;

use App\Models\Producto;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
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
                Repeater::make('items')
                    ->label('Productos de la venta')
                    ->defaultItems(1)
                    ->minItems(1)
                    ->columns(3)
                    ->schema([
                        Select::make('producto_id')
                            ->label('Producto')
                            ->options(fn (): array => Producto::query()->orderBy('nombre')->pluck('nombre', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('cantidad')
                            ->numeric()
                            ->minValue(0.01)
                            ->live(onBlur: true)
                            ->helperText(function (Get $get): ?string {
                                $productoId = (int) ($get('producto_id') ?? 0);

                                if (! $productoId) {
                                    return null;
                                }

                                $producto = Producto::query()->select(['id', 'stock'])->find($productoId);

                                if (! $producto) {
                                    return null;
                                }

                                return 'Stock disponible: '.(float) $producto->stock;
                            })
                            ->rule(function (Get $get): \Closure {
                                return function (string $attribute, mixed $value, \Closure $fail) use ($get): void {
                                    $productoId = (int) ($get('producto_id') ?? 0);
                                    $cantidad = (float) ($value ?? 0);

                                    if (! $productoId || $cantidad <= 0) {
                                        return;
                                    }

                                    $producto = Producto::query()->select(['id', 'nombre', 'stock'])->find($productoId);

                                    if (! $producto) {
                                        return;
                                    }

                                    if ((float) $producto->stock <= 0) {
                                        $fail('El producto '.$producto->nombre.' no tiene stock disponible.');

                                        return;
                                    }

                                    if ($cantidad > (float) $producto->stock) {
                                        $fail('Se intenta vender '.$cantidad.' y solo hay '.(float) $producto->stock.' disponible(s) para '.$producto->nombre.'.');
                                    }
                                };
                            })
                            ->required(),
                        TextInput::make('impuestos')
                            ->default(0)
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
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
                TextInput::make('numero_factura')
                    ->maxLength(255),
                Textarea::make('motivo')
                    ->default('Venta registrada desde panel de ventas')
                    ->columnSpanFull(),
            ]);
    }
}
