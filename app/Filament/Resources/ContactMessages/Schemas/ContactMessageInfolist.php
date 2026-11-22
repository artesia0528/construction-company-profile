<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('phone')->placeholder('-'),
                        TextEntry::make('subject'),
                        TextEntry::make('created_at')->dateTime(),
                        IconEntry::make('is_read')->boolean(),
                        TextEntry::make('message')->columnSpanFull(),
                    ]),
            ]);
    }
}
