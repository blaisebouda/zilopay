<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Volume total', '192.1k')
                ->description('+32k vs ce moi-ci')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Transactions', '21 000')
                ->description('-7% vs ce moi-ci')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Utilisateurs', '1 200')
                ->description('+3% vs ce moi-ci')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Marchands', '200')
                ->description('+1% vs ce moi-ci')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
        ];
    }
}
