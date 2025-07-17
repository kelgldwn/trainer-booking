<?php

namespace App\Filament\Client\Resources;

use App\Models\TrainingSession;
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Client\Resources\TrainingSessionResource\Pages;

class TrainingSessionResource extends Resource
{
    protected static ?string $model = TrainingSession::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Available Sessions';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title'),
            Tables\Columns\TextColumn::make('description'),
            Tables\Columns\TextColumn::make('starts_at')->dateTime(),
            Tables\Columns\TextColumn::make('ends_at')->dateTime(),
            Tables\Columns\TextColumn::make('max_clients')->label('Max Clients'), // ✅ new
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingSessions::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('client');
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
}
