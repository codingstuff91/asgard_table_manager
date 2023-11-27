<?php

namespace App\Filament\Widgets;

use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Utilisateurs', Table::count())
                ->icon('heroicon-o-user'),
            Stat::make('Total jeux', Game::count())
                ->icon('heroicon-o-puzzle-piece'),
            Stat::make('Total Sessions', Day::count()),
            Stat::make('Total tables', Table::count()),
        ];
    }
}
