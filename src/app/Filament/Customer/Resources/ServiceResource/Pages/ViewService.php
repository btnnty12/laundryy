<?php

namespace App\Filament\Customer\Resources\ServiceResource\Pages;

use App\Filament\Customer\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;

class ViewService extends ViewRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit or delete actions for customers
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Service Information')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Service Name')
                            ->size('lg')
                            ->weight('bold'),
                        TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Section::make('Pricing & Duration')
                    ->schema([
                        TextEntry::make('price_per_kg')
                            ->label('Price per Kilogram')
                            ->money('IDR')
                            ->size('lg')
                            ->color('success'),
                        TextEntry::make('estimated_hours')
                            ->label('Estimated Processing Time')
                            ->suffix(' hours')
                            ->size('lg')
                            ->color('primary'),
                        IconEntry::make('is_active')
                            ->label('Service Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])
                    ->columns(3),
                
                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Service Available Since')
                            ->date()
                            ->color('gray'),
                    ])
                    ->collapsible(),
            ]);
    }

    public function getTitle(): string
    {
        return 'Service Details';
    }
}