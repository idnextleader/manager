<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Event;
use App\Models\Team;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Team members', Team::count())
                ->description('Our Team Dedicates')
                ->descriptionIcon('heroicon-o-users')
                ->color('success')
                ->chart([10, 20, 30, 60, 100]),
            Stat::make('Articles', Article::count())
                ->description('Our Articles ')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('primary')
                ->chart([10, 20, 30, 60, 100]),
            Stat::make('Events', Event::count())
                ->description('Our Events ')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('info')
                ->chart([10, 20, 30, 60, 100]),
        ];
    }
}
