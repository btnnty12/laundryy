<?php

namespace App\Filament\Customer\Resources\PaymentResource\Pages;

use App\Filament\Customer\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Support\Enums\FontWeight;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit/delete actions for customers
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Payment Information')
                    ->schema([
                        TextEntry::make('order.order_number')
                            ->label('Order Number')
                            ->weight(FontWeight::Bold)
                            ->copyable(),
                        TextEntry::make('amount')
                            ->label('Payment Amount')
                            ->money('IDR')
                            ->weight(FontWeight::Bold)
                            ->color('success'),
                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'cash' => 'success',
                                'transfer' => 'info',
                                'ewallet' => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'cash' => 'Cash',
                                'transfer' => 'Bank Transfer',
                                'ewallet' => 'E-Wallet',
                                default => $state,
                            }),
                        TextEntry::make('payment_date')
                            ->label('Payment Date')
                            ->date(),
                        TextEntry::make('reference_number')
                            ->label('Reference Number')
                            ->copyable()
                            ->placeholder('No reference number'),
                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Order Information')
                    ->schema([
                        TextEntry::make('order.service.name')
                            ->label('Service'),
                        TextEntry::make('order.weight')
                            ->label('Weight')
                            ->suffix(' kg'),
                        TextEntry::make('order.total_price')
                            ->label('Total Order Price')
                            ->money('IDR'),
                        TextEntry::make('order.status')
                            ->label('Order Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'in_progress' => 'info',
                                'ready' => 'success',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
                        TextEntry::make('order.pickup_date')
                            ->label('Pickup Date')
                            ->date()
                            ->placeholder('Not scheduled'),
                        TextEntry::make('order.delivery_date')
                            ->label('Delivery Date')
                            ->date()
                            ->placeholder('Not scheduled'),
                    ])
                    ->columns(2),
                Section::make('Processing Information')
                    ->schema([
                        TextEntry::make('creator.name')
                            ->label('Processed By'),
                        TextEntry::make('created_at')
                            ->label('Payment Recorded')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])
                    ->columns(3),
            ]);
    }
}