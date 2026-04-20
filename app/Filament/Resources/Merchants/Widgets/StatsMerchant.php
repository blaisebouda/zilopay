<?php

namespace App\Filament\Resources\Merchants\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsMerchant extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $caTotal = \App\Models\MerchantTransaction::success()->sum('amount');
        $totalTransactions = \App\Models\MerchantTransaction::count();
        $totalMerchants = \App\Models\Merchant::count();

        return [
            Stat::make('CA Total', format_amount($caTotal)),
            Stat::make('Total Transactions', $totalTransactions),
            Stat::make('Total Marchands', $totalMerchants),

        ];
    }
}
