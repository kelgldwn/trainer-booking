<?php

namespace App\Filament\Client\Resources\TrainingSessionResource\Pages;

use App\Filament\Client\Resources\TrainingSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingSessions extends ListRecords
{
    protected static string $resource = TrainingSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
