<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom & Prénom')
                    ->searchable(),
                TextColumn::make('email')
                    ->placeholder('-')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->label('Numéro de téléphone')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state->label())
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state->format('d M Y, H:i'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->formatStateUsing(fn($state) => $state->label())
                    ->badge()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),

            ]);
    }
}
