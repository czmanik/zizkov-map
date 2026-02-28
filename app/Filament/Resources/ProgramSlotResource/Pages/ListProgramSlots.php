<?php

namespace App\Filament\Resources\ProgramSlotResource\Pages;

use App\Filament\Resources\ProgramSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProgramSlots extends ListRecords
{
    protected static string $resource = ProgramSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
