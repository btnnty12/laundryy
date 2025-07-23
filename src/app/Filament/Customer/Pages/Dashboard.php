<?php

namespace App\Filament\Customer\Pages;

use App\Filament\Customer\Widgets\CustomerStatsWidget;
use App\Filament\Customer\Widgets\ServicesOverviewWidget;
use App\Filament\Customer\Widgets\RecentPaymentsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            CustomerStatsWidget::class,
            ServicesOverviewWidget::class,
            RecentPaymentsWidget::class,
        ];
    }

    public function getTitle(): string
    {
        return 'Customer Dashboard';
    }

    public function getHeading(): string
    {
        return 'Welcome to Your Laundry Portal';
    }

    public function getSubheading(): ?string
    {
        return 'Manage your orders, view services, and track payments all in one place.';
    }
}