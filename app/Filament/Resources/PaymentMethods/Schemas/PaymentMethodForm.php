<?php

namespace App\Filament\Resources\PaymentMethods\Schemas;

use App\Models\Enums\Country;
use App\Models\Enums\PaymentMethodCode;
use App\Models\Enums\PaymentMethodType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Nom')
                    ->required(),
                Select::make('country')
                    ->label('Pays')
                    ->options(Country::class)
                    ->default(Country::BF)
                    ->required(),
                Select::make('code')
                    ->label('Code')
                    ->options(PaymentMethodCode::class)
                    ->required(),
                Select::make('type')
                    ->label('Type')
                    ->options(PaymentMethodType::class)
                    ->default(PaymentMethodType::MOBILE_MONEY)
                    ->required(),
                TextInput::make('min_amount')
                    ->label('Montant minimum')
                    ->required()
                    ->numeric(),
                TextInput::make('max_amount')
                    ->label('Montant maximum')
                    ->required()
                    ->numeric(),
                TextInput::make('fee_percent')
                    ->label('Frais percentage')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('fee_fixed')
                    ->label('Frais fixe')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->avatar()
                    ->maxSize(1024)
                    ->disk('public')
                    ->directory(PAYMENT_METHOD_LOGO_PATH)
                    ->required(),

            ]);
    }
}
