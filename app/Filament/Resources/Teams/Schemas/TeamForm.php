<?php

namespace App\Filament\Resources\Teams\Schemas;

use App\Support\ImageProcessor;
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
                            ->disk(config('filesystems.media_disk', 'public'))
                            ->visibility('public')
                            ->directory('teams')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'teams'))
                            ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null),
                        TextInput::make('photo_alt_text')->maxLength(255),
                        TextInput::make('display_order')->numeric()->default(0)->required(),
                        Toggle::make('is_active')->default(true)->required(),
                    ]),
            ]);
    }
}
