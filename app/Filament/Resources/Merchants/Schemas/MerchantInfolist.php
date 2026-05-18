<?php

namespace App\Filament\Resources\Merchants\Schemas;

use App\Models\Merchant;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

class MerchantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $merchant = $schema->getRecord()->load('documents');

        return $schema
            ->columns(3)
            ->schema([
                Section::make('Informations du merchant')

                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
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
                            ->formatStateUsing(fn ($state) => $state->label())
                            ->badge()
                            ->color('info'),
                        TextEntry::make('fee_fixed')
                            ->label('Frais fixe')
                            ->formatStateUsing(fn () => $merchant->feeFixedLabel()),
                        TextEntry::make('fee_percent')
                            ->label('Frais percentage')
                            ->formatStateUsing(fn () => $merchant->feePercentLabel())
                            ->badge(),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn ($state) => $state->color())
                            ->formatStateUsing(fn ($state) => $state->label()),
                        TextEntry::make('approved_at')
                            ->label('Date d\'approbation')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('approved_by')
                            ->label('Approuvé par')
                            ->getStateUsing(fn () => $merchant->approver?->name)
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Date de création')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Date de modification')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),

                self::getDocumentsSection($merchant),
            ]);
    }

    private static function getDocumentsSection(Merchant $merchant)
    {

        $documents = $merchant->documents;

        return Section::make('Documents')
            ->schema([
                ...($documents->isNotEmpty() ? $documents->map(function ($document) {
                    return TextEntry::make("DOC_{$document->id}")
                        ->label($document->type->label())
                        ->url(fn () => route('filament.merchant.download', ['path' => $document->path]))
                        ->openUrlInNewTab()
                        ->icon(Heroicon::ArrowTopRightOnSquare)
                        ->iconPosition(IconPosition::After)
                        ->placeholder('Voir le document');
                }) : []),
                ...($documents->isEmpty() ? [
                    TextEntry::make('no_documents')
                        ->label('Aucun document')
                        ->placeholder('Aucun document trouvé')
                        ->columnSpanFull(),
                ] : []),
            ]);
    }
}
