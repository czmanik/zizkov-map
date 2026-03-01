<?php

namespace App\Filament\Widgets;

use App\Models\ProgramSlot;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class PendingProgramSlots extends BaseWidget
{
    protected static ?string $heading = 'Programové sloty čekající na schválení';
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->isAdmin() || Auth::user()->isSuperAdmin();
    }

    public function table(Table $table): Table
    {
        $query = ProgramSlot::where('status', 'pending');

        if (!Auth::user()->isSuperAdmin()) {
            $query->whereHas('stage.venue', fn($q) => $q->where('owner_id', Auth::id()));
        }

        return $table
            ->query(
                $query->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Název')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stage.venue.name')
                    ->label('Místo'),
                Tables\Columns\TextColumn::make('stage.name')
                    ->label('Stage'),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Čas')
                    ->dateTime('d.m. H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Schválit')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(fn (ProgramSlot $record) => $record->update(['status' => 'approved'])),
                Tables\Actions\Action::make('edit')
                    ->label('Upravit')
                    ->url(fn (ProgramSlot $record): string => "/admin/program-slots/{$record->id}/edit"),
            ]);
    }
}
