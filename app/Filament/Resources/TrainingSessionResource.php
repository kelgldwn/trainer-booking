<?php

namespace App\Filament\Resources;
use App\Filament\Resources\TrainingSessionResource\Pages;
use App\Filament\Resources\TrainingSessionResource\RelationManagers;
use Filament\Forms\Form;
use App\Models\TrainingSession;
use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Carbon;


class TrainingSessionResource extends Resource
{
    protected static ?string $model = TrainingSession::class;
    protected static ?string $navigationGroup = 'Training Management';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('coach_id')
                ->label('Coach')
                ->options(User::role('coach')->pluck('name', 'id'))
                ->searchable()
                ->required(),
    
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('description')->maxLength(500),
    
            DateTimePicker::make('starts_at')
                ->label('Start Date & Time')
                ->required()
                ->afterOrEqual(now())
                ->native(false),
    
            DateTimePicker::make('ends_at')
                ->label('End Date & Time')
                ->required()
                ->afterOrEqual('starts_at')
                ->native(false),
    
            TextInput::make('max_clients')
                ->label('Max Clients')
                ->numeric()
                ->minValue(1)
                ->default(10)
                ->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('title'),
        Tables\Columns\TextColumn::make('coach.name')->label('Coach'),
        Tables\Columns\TextColumn::make('starts_at')->dateTime(),
        Tables\Columns\TextColumn::make('ends_at')->dateTime(),
        Tables\Columns\TextColumn::make('max_clients')->label('Max Clients'), // ✅ new
    ])
    ->filters([])
    ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
    ])
    ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
    ]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingSessions::route('/'),
            'create' => Pages\CreateTrainingSession::route('/create'),
            'edit' => Pages\EditTrainingSession::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }
}