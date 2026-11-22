<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Team Member')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->required()->maxLength(255),
                        TextInput::make('position')->maxLength(255),
                        Textarea::make('biography')->rows(5)->columnSpanFull(),
                    ]),
                Section::make('Display')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('profile_photo')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('teams'),
                        TextInput::make('display_order')->numeric()->default(0)->required(),
                        Toggle::make('is_active')->default(true)->required(),
                    ]),
            ]);
    }
}
