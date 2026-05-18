<?php

namespace App\Filament\Resources\PaymentMethods\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentMethodInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $method = $schema->getRecord();

        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nom'),
                ImageEntry::make('logo')
                    ->disk('public')
                    ->imageHeight(50),
                TextEntry::make('country')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->label('Pays'),
                TextEntry::make('code')
                    ->badge(),

                TextEntry::make('min_amount')
                    ->label('Montant minimum')
                    ->formatStateUsing(fn () => $method->minAmountLabel()),
                TextEntry::make('max_amount')
                    ->label('Montant maximum')
                    ->formatStateUsing(fn () => $method->maxAmountLabel()),
                TextEntry::make('type')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->badge(),
                TextEntry::make('fee_percent')
                    ->label('Frais percentage')
                    ->formatStateUsing(fn () => $method->feePercentLabel())
                    ->badge(),
                TextEntry::make('fee_fixed')
                    ->label('Frais fixe')
                    ->formatStateUsing(fn () => $method->feeFixedLabel())
                    ->badge(),
                TextEntry::make('status')
                    ->label('Statut')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->badge(),
                TextEntry::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
