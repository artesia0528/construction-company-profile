<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TeamInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('position')
                    ->placeholder('-'),
                TextEntry::make('biography')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('profile_photo')
                    ->placeholder('-'),
                TextEntry::make('display_order')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
