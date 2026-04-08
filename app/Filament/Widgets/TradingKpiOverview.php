<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use App\Models\Inventario;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class TradingKpiOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $today = now()->startOfDay();

        $ventasHoy = (float) Factura::query()
            ->where('fecha_emicion', '>=', $today)
            ->sum('total');

        $comprasHoy = (float) Inventario::query()
            ->where('tipo_movimiento', 'entrada')
            ->where('fecha_movimiento', '>=', $today)
            ->selectRaw('COALESCE(SUM(precio * cantidad), 0) as total')
            ->value('total');

        $stockValorizado = (float) DB::table('productos')
            ->selectRaw('COALESCE(SUM(stock * costo_promedio), 0) as total')
            ->value('total');

        $facturasPendientes = Factura::query()
            ->where('estado', 'Pendiente')
            ->count();

        return [
            Stat::make('Ventas de hoy', $this->currency($ventasHoy))
                ->description('Flujo de caja de salidas registradas')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Compras de hoy', $this->currency($comprasHoy))
                ->description('Costo de entradas de inventario')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),
            Stat::make('Inventario valorizado', $this->currency($stockValorizado))
                ->description('Valor estimado al costo promedio')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
            Stat::make('Facturas pendientes', number_format($facturasPendientes, 0, ',', '.'))
                ->description('Documentos por cobrar')
                ->descriptionIcon('heroicon-m-clock')
                ->color($facturasPendientes > 0 ? 'danger' : 'success'),
        ];
    }

    private function currency(float $value): string
    {
        return '$ '.number_format($value, 2, ',', '.');
    }
}
