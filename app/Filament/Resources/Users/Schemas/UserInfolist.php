<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nom')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Adresse email')
                    ->placeholder('-'),
                TextEntry::make('role')
                    ->label('Rôle')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->badge(),
                TextEntry::make('phone_number')
                    ->label('Numéro de téléphone')
                    ->placeholder('-'),

                TextEntry::make('email_verified_at')
                    ->label('Email vérifié le')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('phone_verified_at')
                    ->label('Téléphone vérifié le')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('policy_accepted_at')
                    ->label('Politique acceptée le')
                    ->dateTime()
                    ->placeholder('-'),
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
