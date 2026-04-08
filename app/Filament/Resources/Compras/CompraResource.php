<?php

namespace App\Filament\Resources\Compras;

use App\Filament\Resources\Compras\Pages\CreateCompra;
use App\Filament\Resources\Compras\Pages\ListCompras;
use App\Filament\Resources\Compras\Schemas\CompraForm;
use App\Filament\Resources\Compras\Tables\ComprasTable;
use App\Models\Inventario;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompraResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?int $navigationSort = 45;

    public static function getNavigationGroup(): ?string
    {
        return 'Gestion Comercial';
    }

    public static function getNavigationLabel(): string
    {
        return 'Compras';
    }

    public static function getModelLabel(): string
    {
        return 'compra';
    }

    public static function getPluralModelLabel(): string
    {
        return 'compras';
    }

    public static function form(Schema $schema): Schema
    {
        return CompraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComprasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tipo_movimiento', 'entrada');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompras::route('/'),
            'create' => CreateCompra::route('/create'),
        ];
    }
}
