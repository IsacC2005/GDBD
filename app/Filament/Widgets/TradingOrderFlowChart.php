<?php

namespace App\Filament\Widgets;

use App\Models\Inventario;
use Filament\Widgets\ChartWidget;

class TradingOrderFlowChart extends ChartWidget
{
    protected ?string $heading = 'Flujo de ordenes (14 dias)';

    protected int|string|array $columnSpan = 2;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $labels = [];
        $entradas = [];
        $salidas = [];
        $index = [];

        $startDay = now()->startOfDay()->subDays(13);

        for ($i = 0; $i < 14; $i++) {
            $day = $startDay->copy()->addDays($i);
            $key = $day->format('Y-m-d');

            $labels[] = $day->format('d/m');
            $index[$key] = $i;
            $entradas[$i] = 0;
            $salidas[$i] = 0;
        }

        Inventario::query()
            ->where('fecha_movimiento', '>=', $startDay)
            ->get(['fecha_movimiento', 'tipo_movimiento', 'cantidad'])
            ->each(function (Inventario $movimiento) use (&$entradas, &$salidas, $index): void {
                $key = $movimiento->fecha_movimiento?->format('Y-m-d');

                if ($key === null || ! array_key_exists($key, $index)) {
                    return;
                }

                $position = $index[$key];

                if ($movimiento->tipo_movimiento === 'entrada') {
                    $entradas[$position] += (float) $movimiento->cantidad;

                    return;
                }

                if ($movimiento->tipo_movimiento === 'salida') {
                    $salidas[$position] += (float) $movimiento->cantidad;
                }
            });

        return [
            'datasets' => [
                [
                    'label' => 'Entradas',
                    'data' => array_map(static fn (float $value): float => round($value, 2), $entradas),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.75)',
                    'borderColor' => '#10b981',
                ],
                [
                    'label' => 'Salidas',
                    'data' => array_map(static fn (float $value): float => round($value, 2), $salidas),
                    'backgroundColor' => 'rgba(244, 63, 94, 0.75)',
                    'borderColor' => '#f43f5e',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
