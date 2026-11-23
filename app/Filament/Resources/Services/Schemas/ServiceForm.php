<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Support\ImageProcessor;
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
                            ->disk(config('filesystems.media_disk', 'public'))
                            ->visibility('public')
                            ->directory('services')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'services'))
                            ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ]),
                Section::make('SEO')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('seo_title')->maxLength(255),
                        TextInput::make('canonical_url')->maxLength(2048)->url(),
                        Textarea::make('seo_description')->rows(3)->columnSpanFull(),
                        FileUpload::make('seo_image')
                            ->image()
                            ->disk(config('filesystems.media_disk', 'public'))
                            ->visibility('public')
                            ->directory('services/seo')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'services/seo'))
                            ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null),
                        Toggle::make('noindex')->label('Noindex this service'),
                    ]),
            ]);
    }
}
