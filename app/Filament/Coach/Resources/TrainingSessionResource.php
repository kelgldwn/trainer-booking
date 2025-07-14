<?php

namespace App\Filament\Coach\Resources;

use App\Models\TrainingSession;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Coach\Resources\TrainingSessionResource\Pages;
use Illuminate\Support\Carbon;


class TrainingSessionResource extends Resource
{
    protected static ?string $model = TrainingSession::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Training';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('coach_id', auth()->id());
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('description')->maxLength(500),
    
            DateTimePicker::make('starts_at')
                ->label('Start Date & Time')
                ->required()
                ->afterOrEqual(now()) // ✅ Filament way
                ->native(false),
    
            DateTimePicker::make('ends_at')
                ->label('End Date & Time')
                ->required()
                ->afterOrEqual('starts_at') // ✅ Reference to another field
                ->native(false),
        ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['coach_id'] = auth()->id();
        return $data;
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title'),
            Tables\Columns\TextColumn::make('starts_at')->dateTime(),
            Tables\Columns\TextColumn::make('ends_at')->dateTime(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
}
