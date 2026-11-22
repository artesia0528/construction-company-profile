<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Projects', Project::query()->count()),
            Stat::make('Total Services', Service::query()->count()),
            Stat::make('Total Testimonials', Testimonial::query()->count()),
            Stat::make('Total Contact Messages', ContactMessage::query()->count()),
        ];
    }
}
