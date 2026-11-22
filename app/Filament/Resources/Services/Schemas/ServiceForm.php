<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                                if (blank($get('slug'))) {
                                    $set('slug', Str::slug((string) $state));
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('short_description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('full_description')
                            ->rows(8)
                            ->columnSpanFull(),
                    ]),
                Section::make('Display')
                    ->columns(2)
                    ->schema([
                        TextInput::make('icon')
                            ->maxLength(255)
                            ->helperText('Optional icon name or label for internal reference.'),
                        TextInput::make('display_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        FileUpload::make('thumbnail_image')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('services')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
