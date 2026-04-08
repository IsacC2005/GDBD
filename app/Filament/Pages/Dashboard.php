<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\TradingKpiOverview;
use App\Filament\Widgets\TradingMonthlyPerformanceChart;
use App\Filament\Widgets\TradingOrderFlowChart;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Trading Desk';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    public function getHeading(): string
    {
        return 'Trading Desk';
    }

    public function getSubheading(): ?string
    {
        return 'Monitorea ventas, compras y flujo de inventario en tiempo real.';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TradingKpiOverview::class,
            TradingMonthlyPerformanceChart::class,
            TradingOrderFlowChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }
}
