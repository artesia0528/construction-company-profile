<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Support\ImageProcessor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client')
                    ->columns(2)
                    ->schema([
                        TextInput::make('client_name')->required()->maxLength(255),
                        TextInput::make('client_position')->maxLength(255),
                        TextInput::make('company_name')->maxLength(255),
                        TextInput::make('rating')->numeric()->minValue(1)->maxValue(5)->default(5)->required(),
                        Textarea::make('testimonial_content')->required()->rows(5)->columnSpanFull(),
                    ]),
                Section::make('Display')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('client_photo')
                            ->image()
                            ->disk(config('filesystems.media_disk', 'public'))
                            ->visibility('public')
                            ->directory('testimonials')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'testimonials'))
                            ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null),
                        TextInput::make('photo_alt_text')->maxLength(255),
                        Toggle::make('is_active')->default(true)->required(),
                    ]),
            ]);
    }
}
