<?php

namespace App\Filament\Resources\Statistics\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StatisticForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Statistic')
                    ->columns(2)
                    ->schema([
                        TextInput::make('label')->required()->maxLength(255),
                        TextInput::make('value')->required()->maxLength(255),
                        TextInput::make('icon')->maxLength(255),
                        TextInput::make('display_order')->numeric()->default(0)->required(),
                        Toggle::make('is_active')->default(true)->required(),
                    ]),
            ]);
    }
}
