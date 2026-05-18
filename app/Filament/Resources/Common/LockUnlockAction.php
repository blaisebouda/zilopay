<?php

namespace App\Filament\Resources\Common;

use Filament\Actions\Action;

class LockUnlockAction
{
    public static function make(): Action
    {
        return Action::make('toggle_active')
            ->label(fn ($record) => $record->isActive() ? 'Bloquer' : 'Débloquer')
            ->icon(fn ($record) => $record->isActive() ? 'heroicon-m-lock-open' : 'heroicon-m-lock-closed')
            ->color(fn ($record) => $record->isActive() ? 'success' : 'danger')
            ->requiresConfirmation()
            ->modalHeading('Confirmer le changement')
            ->modalDescription('Êtes-vous sûr de vouloir changer le statut de cet enregistrement ?')
            ->visible(auth()->user()->isAdmin())
            ->action(fn ($record) => self::toggle($record));
    }

    public static function toggle($record): void
    {
        if (auth()->user()->isAdmin()) {
            $record->toggle();
        }
    }
}
