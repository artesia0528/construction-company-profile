<?php

namespace App\Filament\Resources\Projects\Schemas;

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
                            ->disk('public')
                            ->visibility('public')
                            ->directory('projects/covers'),
                        Repeater::make('images')
                            ->relationship()
                            ->defaultItems(0)
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('projects/gallery')
                                    ->required(),
                                TextInput::make('alt_text')->maxLength(255),
                                TextInput::make('sort_order')->numeric()->default(0),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
