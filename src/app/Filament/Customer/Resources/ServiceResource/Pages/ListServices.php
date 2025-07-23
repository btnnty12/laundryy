<?php

namespace App\Filament\Customer\Resources\ServiceResource\Pages;

use App\Filament\Customer\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action for customers
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Services')
                ->badge(fn () => \App\Models\Service::where('is_active', true)->count()),
            'washing' => Tab::make('Washing Services')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('name', 'like', '%wash%'))
                ->badge(fn () => \App\Models\Service::where('is_active', true)->where('name', 'like', '%wash%')->count()),
            'drying' => Tab::make('Drying Services')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('name', 'like', '%dry%'))
                ->badge(fn () => \App\Models\Service::where('is_active', true)->where('name', 'like', '%dry%')->count()),
            'ironing' => Tab::make('Ironing Services')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('name', 'like', '%iron%'))
                ->badge(fn () => \App\Models\Service::where('is_active', true)->where('name', 'like', '%iron%')->count()),
        ];
    }

    public function getTitle(): string
    {
        return 'Available Services';
    }

    public function getHeading(): string
    {
        return 'Our Laundry Services';
    }

    public function getSubheading(): string
    {
        return 'Browse our available laundry services and their pricing.';
    }
}