<?php

namespace App\Filament\Customer\Resources\PaymentResource\Pages;

use App\Filament\Customer\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action for customers
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Payments')
                ->badge(fn () => $this->getModel()::whereHas('order', function (Builder $query) {
                    $query->where('customer_id', auth()->id());
                })->count()),
            'cash' => Tab::make('Cash Payments')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_method', 'cash'))
                ->badge(fn () => $this->getModel()::whereHas('order', function (Builder $query) {
                    $query->where('customer_id', auth()->id());
                })->where('payment_method', 'cash')->count()),
            'transfer' => Tab::make('Bank Transfer')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_method', 'transfer'))
                ->badge(fn () => $this->getModel()::whereHas('order', function (Builder $query) {
                    $query->where('customer_id', auth()->id());
                })->where('payment_method', 'transfer')->count()),
            'ewallet' => Tab::make('E-Wallet')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_method', 'ewallet'))
                ->badge(fn () => $this->getModel()::whereHas('order', function (Builder $query) {
                    $query->where('customer_id', auth()->id());
                })->where('payment_method', 'ewallet')->count()),
        ];
    }
}