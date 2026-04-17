<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
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



            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('toggle_active')
                    ->label(fn($record) => $record->is_active ? 'Débloquer' : 'Bloquer')
                    ->icon(fn($record) => $record->is_active ? 'heroicon-m-lock-open' : 'heroicon-m-lock-closed')

                    ->requiresConfirmation() // Affiche la fenêtre de confirmation
                    ->modalHeading('Confirmer le changement')
                    ->modalDescription('Êtes-vous sûr de vouloir changer le statut de cet enregistrement ?')
                    ->action(fn($record) => $record->update(['is_active' => !$record->is_active]))

            ]);
    }
}
