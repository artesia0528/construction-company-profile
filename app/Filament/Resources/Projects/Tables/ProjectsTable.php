<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use App\Support\ImageProcessor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->disk(config('filesystems.media_disk', 'public'))
                    ->getStateUsing(fn (Project $record): ?string => filled($record->cover_image) ? (ImageProcessor::thumbPath($record->cover_image) ?: $record->cover_image) : null)
                    ->square(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('category')->searchable()->sortable(),
                TextColumn::make('location')->searchable()->toggleable(),
                TextColumn::make('completion_year')->sortable(),
                TextColumn::make('status')->badge(),
                IconColumn::make('is_featured')->boolean(),
                IconColumn::make('is_published')->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(fn (): array => Project::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category', 'category')->all()),
                SelectFilter::make('status')
                    ->options(['completed' => 'Completed', 'in_progress' => 'In Progress']),
            ])
            ->defaultSort('completion_year', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
