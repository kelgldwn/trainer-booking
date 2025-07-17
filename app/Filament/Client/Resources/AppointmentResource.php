<?php

namespace App\Filament\Client\Resources;

use App\Models\Appointment;
use App\Models\TrainingSession;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use App\Filament\Client\Resources\AppointmentResource\Pages;
use Illuminate\Validation\ValidationException;
use App\Notifications\AppointmentCancelledByClient;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $navigationGroup = 'My Bookings';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('client_id', auth()->id());
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('training_session_id')
                ->label('Choose a Session')
                ->options(TrainingSession::pluck('title', 'id'))
                ->searchable()
                ->required(),
        ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('trainingSession.title')->label('Session'),

            Tables\Columns\TextColumn::make('trainingSession.coach.name')
                ->label('Coach')
                ->searchable(),

            Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                'pending' => 'gray',
                'approved' => 'success',
                'rejected' => 'danger',
                'cancelled' => 'warning',
                default => 'secondary',
            }),

            Tables\Columns\TextColumn::make('created_at')->label('Booked At')->dateTime(),
        ])
        ->actions([
            Tables\Actions\Action::make('cancel')
                ->label('Cancel')
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->status === 'pending')
                ->action(function ($record) {
                    $record->update(['status' => 'cancelled']);

                    // ✅ Notify the coach
                    if ($record->trainingSession && $record->trainingSession->coach) {
                        $record->trainingSession->coach->notify(
                            new AppointmentCancelledByClient($record)
                        );
                    }
                }),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('client');
    }

    public static function canDelete($record): bool { return false; }
    public static function canEdit($record): bool { return false; }
}
