<?php

namespace App\Filament\Resources\Merchants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MerchantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $merchant = $schema->getRecord();

        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('Nom du propriétaire'),
                TextEntry::make('business_name')
                    ->label('Nom de l\'entreprise'),
                TextEntry::make('business_email')
                    ->label('Email de l\'entreprise'),
                TextEntry::make('phone_number')
                    ->label('Numéro de téléphone')
                    ->placeholder('-'),
                TextEntry::make('country')
                    ->formatStateUsing(fn($state) => $state->label())
                    ->badge()
                    ->color('info'),
                TextEntry::make('fee_fixed')
                    ->label('Frais fixe')
                    ->formatStateUsing(fn() => $merchant->feeFixedLabel()),
                TextEntry::make('fee_percent')
                    ->label('Frais percentage')
                    ->formatStateUsing(fn() => $merchant->feePercentLabel())
                    ->badge(),
                TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state->label()),
                TextEntry::make('approved_at')
                    ->label('Date d\'approbation')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('approved_by')
                    ->label('Approuvé par')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Date de création')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Date de modification')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
