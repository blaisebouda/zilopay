<?php

namespace App\Filament\Resources\Merchants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MerchantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('business_name'),
                TextEntry::make('business_email'),
                TextEntry::make('phone_number')
                    ->placeholder('-'),
                TextEntry::make('country')
                    ->badge(),
                TextEntry::make('fee_fixed')
                    ->numeric(),
                TextEntry::make('fee_percentage')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge()
                    ->numeric(),
                TextEntry::make('approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
