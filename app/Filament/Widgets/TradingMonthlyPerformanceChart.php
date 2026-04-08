<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use App\Models\Inventario;
use Filament\Widgets\ChartWidget;

class TradingMonthlyPerformanceChart extends ChartWidget
{
    protected ?string $heading = 'Performance mensual (12 meses)';

    protected int|string|array $columnSpan = 2;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $labels = [];
        $ventas = [];
        $compras = [];
        $index = [];

        $startMonth = now()->startOfMonth()->subMonths(11);

        for ($i = 0; $i < 12; $i++) {
            $month = $startMonth->copy()->addMonths($i);
            $key = $month->format('Y-m');

            $labels[] = $month->translatedFormat('M y');
            $index[$key] = $i;
            $ventas[$i] = 0;
            $compras[$i] = 0;
        }

        Factura::query()
            ->where('fecha_emicion', '>=', $startMonth)
            ->get(['fecha_emicion', 'total'])
            ->each(function (Factura $factura) use (&$ventas, $index): void {
                $key = $factura->fecha_emicion?->format('Y-m');

                if ($key !== null && array_key_exists($key, $index)) {
                    $ventas[$index[$key]] += (float) $factura->total;
                }
            });

        Inventario::query()
            ->where('tipo_movimiento', 'entrada')
            ->where('fecha_movimiento', '>=', $startMonth)
            ->get(['fecha_movimiento', 'precio', 'cantidad'])
            ->each(function (Inventario $movimiento) use (&$compras, $index): void {
                $key = $movimiento->fecha_movimiento?->format('Y-m');

                if ($key !== null && array_key_exists($key, $index)) {
                    $compras[$index[$key]] += (float) $movimiento->precio * (float) $movimiento->cantidad;
                }
            });

        return [
            'datasets' => [
                [
                    'label' => 'Ventas',
                    'data' => array_map(static fn (float $value): float => round($value, 2), $ventas),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Compras',
                    'data' => array_map(static fn (float $value): float => round($value, 2), $compras),
                    'borderColor' => '#f97316',
                    'backgroundColor' => 'rgba(249, 115, 22, 0.10)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
