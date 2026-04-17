<?php

namespace App\Filament\Resources\PaymentMethods\Tables;

use App\Filament\Resources\Common\LockUnlockAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentMethodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                ImageColumn::make('logo')
                    ->disk('public')
                    ->visibility('public')
                    ->square()
                    ->circular(),

                TextColumn::make('country')
                    ->label('Pays')
                    ->formatStateUsing(fn ($state) => $state->label()),

                TextColumn::make('amount_range')
                    ->label('Plage de recharge')
                    ->state(fn ($record) => $record->amountRangeLabel()),

                TextColumn::make('fee_percent')
                    ->label('Frais %')
                    ->formatStateUsing(fn ($state) => $state.' %')
                    ->badge()
                    ->sortable(),

                TextColumn::make('fee_fixed')
                    ->label('Frais fixe')
                    ->badge()
                    ->numeric()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label()),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    LockUnlockAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
