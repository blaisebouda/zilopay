<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Common\LockUnlockAction;
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
                LockUnlockAction::make()
            ]);
    }
}
