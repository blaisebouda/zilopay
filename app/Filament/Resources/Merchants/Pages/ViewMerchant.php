<?php

namespace App\Filament\Resources\Merchants\Pages;

use App\Filament\Resources\Merchants\MerchantResource;
use App\Models\Enums\MerchantStatus;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\MarkdownEditor;

class ViewMerchant extends ViewRecord
{
    protected static string $resource = MerchantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            $this->ApproveAction(),
            $this->RejectAction(),
        ];
    }

    private function isVisible()
    {
        return auth()->user()->isAdmin() && !$this->record->isApproved();
    }

    protected function ApproveAction()
    {
        return \Filament\Actions\Action::make('approve')
            ->label('Approuver')
            ->icon('heroicon-o-check')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Confirmer l\'approuval')
            ->modalDescription('Êtes-vous sûr de vouloir approuver ce merchant ?')
            ->visible($this->isVisible())
            ->action(fn() => $this->approve());
    }

    private function approve()
    {
        $this->record->update([
            'approved_by' => auth()->user()->id,
            'status' => MerchantStatus::APPROVED,
            'approved_at' => now(),
        ]);
    }

    protected function RejectAction()
    {
        return \Filament\Actions\Action::make('reject')
            ->color('danger')
            ->schema([
                MarkdownEditor::make('rejection_reason')
                    ->label('Raison du rejet')
                    ->required(),
            ])
            ->action(function (array $data): void {
                $this->reject($data['rejection_reason']);
            })
            ->visible($this->isVisible());
    }

    private function reject(string $reason)
    {
        $this->record->update([
            'status' => MerchantStatus::REJECTED,
            'rejection_reason' => $reason,
        ]);
    }
}
