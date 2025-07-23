<?php

namespace App\Filament\Customer\Widgets;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class RecentPaymentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Payments';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->whereHas('order', function (Builder $query) {
                        $query->where('customer_id', Filament::auth()->id());
                    })
                    ->with(['order', 'order.service'])
                    ->latest('payment_date')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label('Method')
                    ->colors([
                        'success' => 'cash',
                        'primary' => 'transfer',
                        'warning' => 'ewallet',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'Cash',
                        'transfer' => 'Bank Transfer',
                        'ewallet' => 'E-Wallet',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Reference')
                    ->searchable()
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Payment $record): string => route('filament.customer.resources.payments.view', $record))
                    ->openUrlInNewTab(false),
            ])
            ->paginated(false)
            ->defaultSort('payment_date', 'desc');
    }

    public function getTableHeading(): string
    {
        return 'Your Recent Payments';
    }

    public function getTableDescription(): string
    {
        return 'Your latest payment transactions. Click "View All Payments" to see your complete payment history.';
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\Action::make('view_all')
                ->label('View All Payments')
                ->icon('heroicon-m-arrow-right')
                ->url(route('filament.customer.resources.payments.index'))
                ->color('primary'),
        ];
    }
}