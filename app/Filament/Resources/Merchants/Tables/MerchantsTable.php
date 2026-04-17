<?php

namespace App\Filament\Resources\Merchants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MerchantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nom du propriétaire')
                    ->searchable(),
                TextColumn::make('business_name')
                    ->label('Nom de l\'entreprise')
                    ->searchable(),
                TextColumn::make('business_email')
                    ->label('Email de l\'entreprise')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->label('Numéro de téléphone')
                    ->searchable(),
                TextColumn::make('country')

                    ->label('Pays')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->badge()
                    ->color('default')
                    ->searchable(),
                TextColumn::make('fee_fixed')
                    ->label('Frais fixe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => number_format($state, 0).' FCFA')
                    ->sortable(),
                TextColumn::make('fee_percentage')
                    ->label('Frais en pourcentage')
                    ->badge()
                    ->formatStateUsing(fn ($state) => number_format($state, 0).' %')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->color())
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
