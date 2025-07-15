<?php

namespace App\Filament\Coach\Resources;

use App\Models\User;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Coach\Resources\ClientResource\Pages;
use Spatie\Permission\Models\Role;

class ClientResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Clients';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->role('Client'); // Filter only clients
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('name'),
            TextColumn::make('email'),
            TextColumn::make('created_at')->label('Registered At')->date(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('coach');
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
}
