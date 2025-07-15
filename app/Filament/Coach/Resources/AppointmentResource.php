<?php

namespace App\Filament\Coach\Resources;

use App\Models\Appointment;
use App\Models\TrainingSession;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Coach\Resources\AppointmentResource\Pages;
use Illuminate\Database\Eloquent\Model;


class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $navigationGroup = 'Bookings';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('trainingSession', function ($q) {
            $q->where('coach_id', auth()->id());
        });
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('status')
                ->label('Booking Status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'cancelled' => 'Cancelled', // ✅ Optional
                ])
                ->required()
                ->native(false),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            TextColumn::make('client.name')->label('Client'),
            TextColumn::make('trainingSession.title')->label('Session'),
            TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                'pending' => 'gray',
                'approved' => 'success',
                'rejected' => 'danger',
                'cancelled' => 'warning',
                default => 'secondary',
            }),
            TextColumn::make('created_at')->label('Booked At')->dateTime(),
        ])
        ->actions([
            Tables\Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn ($record) => $record->status === 'pending')
                ->action(fn ($record) => $record->update(['status' => 'approved'])),

            Tables\Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn ($record) => $record->status === 'pending')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update(['status' => 'rejected'])),
        ]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('coach');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
{
    return false;
}

}
