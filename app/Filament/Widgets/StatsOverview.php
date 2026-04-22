<?php

namespace App\Filament\Widgets;

use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\User;
use App\Utils\QueryTrend;
use App\Utils\UpDown;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $txSum =  $txCount = UpDown::make(QueryTrend::make(Transaction::class)->lastMonths()->sum('amount')->values());;

        $txCount = UpDown::make(QueryTrend::make(Transaction::class)->lastMonths()->count()->values());

        $userCount = UpDown::make(QueryTrend::make(User::class)->lastMonths()->count()->values());

        $merchantCount = UpDown::make(QueryTrend::make(Merchant::class)->lastMonths()->count()->values());


        return [
            Stat::make('Volume total', Transaction::completed()->sum('amount'))
                ->description($txSum->formatPercentage() . ' vs ce moi-ci')
                ->descriptionIcon($txSum->isUp() ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($txSum->isUp() ? 'success' : 'danger'),
            Stat::make('Transactions', Transaction::count())
                ->description($txCount->formatPercentage() . ' vs ce moi-ci')
                ->descriptionIcon($txCount->isUp() ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($txCount->isUp() ? 'success' : 'danger'),
            Stat::make('Utilisateurs', User::count())
                ->description($userCount->formatPercentage() . ' vs ce moi-ci')
                ->descriptionIcon($userCount->isUp() ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($userCount->isUp() ? 'success' : 'danger'),
            Stat::make('Marchands', Merchant::count())
                ->description($merchantCount->formatPercentage() . ' vs ce moi-ci')
                ->descriptionIcon($merchantCount->isUp() ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($merchantCount->isUp() ? 'success' : 'danger')
        ];
    }
}
