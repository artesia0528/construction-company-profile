<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Support\ImageProcessor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
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
                Section::make('Metadata')
                    ->columns(2)
                    ->schema([
                        TextInput::make('category')->maxLength(255),
                        TextInput::make('location')->maxLength(255),
                        TextInput::make('client_name')->maxLength(255),
                        TextInput::make('completion_year')->numeric()->minValue(1900)->maxValue(2100),
                        TextInput::make('status')->required()->default('completed')->maxLength(255),
                        Toggle::make('is_featured')->default(false),
                        Toggle::make('is_published')->default(true),
                    ]),
                Section::make('Images')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->image()
                            ->disk(config('filesystems.media_disk', 'public'))
                            ->visibility('public')
                            ->directory('projects/covers')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'projects/covers'))
                            ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null),
                        TextInput::make('cover_alt_text')->maxLength(255)->columnSpanFull(),
                        Repeater::make('images')
                            ->relationship()
                            ->defaultItems(0)
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Image')
                                    ->image()
                                    ->disk(config('filesystems.media_disk', 'public'))
                                    ->visibility('public')
                                    ->directory('projects/gallery')
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'projects/gallery'))
                                    ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null)
                                    ->required(),
                                TextInput::make('alt_text')->maxLength(255),
                                TextInput::make('sort_order')->numeric()->default(0),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
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
                            ->directory('projects/seo')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'projects/seo'))
                            ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null),
                        Toggle::make('noindex')->label('Noindex this project'),
                    ]),
            ]);
    }
}
