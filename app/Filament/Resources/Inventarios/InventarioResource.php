<?php

namespace App\Filament\Resources\Inventarios;

use App\Filament\Resources\Inventarios\Pages\CreateInventario;
use App\Filament\Resources\Inventarios\Pages\EditInventario;
use App\Filament\Resources\Inventarios\Pages\ListInventarios;
use App\Filament\Resources\Inventarios\Schemas\InventarioForm;
use App\Filament\Resources\Inventarios\Tables\InventariosTable;
use App\Models\Inventario;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 50;

    public static function getNavigationGroup(): ?string
    {
        return 'Gestión Comercial';
    }

    public static function getNavigationLabel(): string
    {
        return 'Inventario';
    }

    public static function getModelLabel(): string
    {
        return 'movimiento';
    }

    public static function getPluralModelLabel(): string
    {
        return 'inventario';
    }

    public static function form(Schema $schema): Schema
    {
        return InventarioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventariosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventarios::route('/'),
            'create' => CreateInventario::route('/create'),
            'edit' => EditInventario::route('/{record}/edit'),
        ];
    }
}
