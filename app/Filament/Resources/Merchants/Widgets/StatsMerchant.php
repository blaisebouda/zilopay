<?php

namespace App\Filament\Resources\Merchants\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsMerchant extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Marchands', '192.1k'),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '3:12'),
        ];
    }
}
