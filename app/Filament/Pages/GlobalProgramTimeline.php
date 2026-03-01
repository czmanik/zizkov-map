<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GlobalProgramTimeline extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Program';
    protected static ?string $title = 'Celkový přehled programu';
    protected static ?string $navigationLabel = 'Celkový přehled';

    protected static string $view = 'filament.pages.global-program-timeline';

    public static function canAccess(): bool
    {
        return auth()->user()->isSuperAdmin();
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\ProgramTimeline::class,
        ];
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 1;
    }
}
