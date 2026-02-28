<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrationStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Celkový počet registrací', User::count())
                ->description('Všichni registrovaní uživatelé')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
        ];
    }
}
