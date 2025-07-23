<?php

namespace App\Filament\Customer\Widgets;

use App\Models\Service;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ServicesOverviewWidget extends BaseWidget
{
    protected static ?string $heading = 'Available Services';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Service::query()->where('is_active', true)->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 40) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('price_per_kg')
                    ->label('Price/Kg')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_hours')
                    ->label('Duration')
                    ->suffix(' hrs')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Available')
                    ->boolean()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View Details')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Service $record): string => route('filament.customer.resources.services.view', $record))
                    ->openUrlInNewTab(false),
            ])
            ->paginated(false)
            ->defaultSort('name');
    }

    public function getTableHeading(): string
    {
        return 'Our Laundry Services';
    }

    public function getTableDescription(): string
    {
        return 'Browse our available services. Click "View All Services" to see the complete list.';
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\Action::make('view_all')
                ->label('View All Services')
                ->icon('heroicon-m-arrow-right')
                ->url(route('filament.customer.resources.services.index'))
                ->color('primary'),
        ];
    }
}