<?php

namespace App\Filament\Resources\Merchants\Schemas;

use App\Models\Enums\Country;
use App\Models\Enums\MerchantStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MerchantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('business_name')
                    ->required(),
                TextInput::make('business_email')
                    ->email()
                    ->required(),
                TextInput::make('phone_number')
                    ->tel(),
                Select::make('country')
                    ->options(Country::class)
                    ->required(),
                TextInput::make('fee_fixed')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('fee_percentage')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options(MerchantStatus::class)
                    ->required()
                    ->default(0),
                DateTimePicker::make('approved_at'),
                TextInput::make('approved_by')
                    ->numeric(),
            ]);
    }
}
