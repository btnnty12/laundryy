<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Customer Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                ImageEntry::make('avatar_url')
                                    ->label('Avatar')
                                    ->defaultImageUrl(url('https://www.gravatar.com/avatar/64e1b8d34f425d19e1ee2ea7236d3028?d=mp&r=g&s=250'))
                                    ->circular()
                                    ->columnSpan(1),
                                Grid::make(1)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Name'),
                                        TextEntry::make('email')
                                            ->label('Email'),
                                        TextEntry::make('roles.name')
                                            ->label('Roles')
                                            ->badge(),
                                    ])
                                    ->columnSpan(2),
                            ]),
                    ]),
                
                Section::make('Order & Payment Statistics')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('total_orders')
                                    ->label('Total Orders')
                                    ->getStateUsing(fn ($record) => $record->orders()->count())
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('pending_orders')
                                    ->label('Pending Orders')
                                    ->getStateUsing(fn ($record) => $record->orders()->where('status', 'pending')->count())
                                    ->badge()
                                    ->color('warning'),
                                TextEntry::make('completed_orders')
                                    ->label('Completed Orders')
                                    ->getStateUsing(fn ($record) => $record->orders()->where('status', 'delivered')->count())
                                    ->badge()
                                    ->color('success'),
                                TextEntry::make('total_paid')
                                    ->label('Total Paid')
                                    ->getStateUsing(fn ($record) => 'Rp ' . number_format($record->payments()->sum('amount'), 0, ',', '.'))
                                    ->badge()
                                    ->color('success'),
                            ]),
                    ]),
                
                Section::make('Recent Activity')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('last_order')
                                    ->label('Last Order')
                                    ->getStateUsing(function ($record) {
                                        $lastOrder = $record->orders()->latest()->first();
                                        return $lastOrder ? $lastOrder->order_number . ' (' . $lastOrder->created_at->diffForHumans() . ')' : 'No orders yet';
                                    }),
                                TextEntry::make('last_payment')
                                    ->label('Last Payment')
                                    ->getStateUsing(function ($record) {
                                        $lastPayment = $record->payments()->latest()->first();
                                        return $lastPayment ? 'Rp ' . number_format($lastPayment->amount, 0, ',', '.') . ' (' . $lastPayment->created_at->diffForHumans() . ')' : 'No payments yet';
                                    })
                            ]),
                    ]),
                
                Section::make('Account Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Member Since')
                                    ->dateTime(),
                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->dateTime(),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}