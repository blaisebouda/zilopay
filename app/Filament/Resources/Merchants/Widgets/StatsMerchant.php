<?php

namespace App\Filament\Resources\Merchants\Widgets;

use App\Models\Merchant;
use App\Models\MerchantTransaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsMerchant extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $caTotal = MerchantTransaction::completed()->sum('amount');
        $totalTransactions = MerchantTransaction::count();
        $totalMerchants = Merchant::count();

        return [
            Stat::make('CA Total', format_amount($caTotal)),
            Stat::make('Total Transactions', $totalTransactions),
            Stat::make('Total Marchands', $totalMerchants),

        ];
    }
}
