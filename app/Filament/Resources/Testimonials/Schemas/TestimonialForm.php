<?php

namespace App\Filament\Resources\Testimonials\Schemas;

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
                            ->disk('public')
                            ->visibility('public')
                            ->directory('testimonials'),
                        Toggle::make('is_active')->default(true)->required(),
                    ]),
            ]);
    }
}
