<?php

namespace App\Filament\Resources\ProgramSlotResource\Pages;

use App\Filament\Resources\ProgramSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditProgramSlot extends EditRecord
{
    protected static string $resource = ProgramSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!Auth::user()->isSuperAdmin() && $this->record->status === 'approved') {
            $data['status'] = 'pending';
        }

        return $data;
    }
}
