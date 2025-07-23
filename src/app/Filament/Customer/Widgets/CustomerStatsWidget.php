<?php

namespace App\Filament\Customer\Widgets;

use App\Models\Payment;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class CustomerStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();
        
        // Get customer's orders
        $totalOrders = Order::where('customer_id', $userId)->count();
        $pendingOrders = Order::where('customer_id', $userId)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
        $completedOrders = Order::where('customer_id', $userId)
            ->whereIn('status', ['ready', 'delivered'])
            ->count();
        
        // Get customer's payments
        $totalPaid = Payment::whereHas('order', function ($query) use ($userId) {
            $query->where('customer_id', $userId);
        })->sum('amount');
        
        $totalPayments = Payment::whereHas('order', function ($query) use ($userId) {
            $query->where('customer_id', $userId);
        })->count();
        
        // Get recent payment
        $lastPayment = Payment::whereHas('order', function ($query) use ($userId) {
            $query->where('customer_id', $userId);
        })->latest()->first();
        
        return [
            Stat::make('Total Orders', $totalOrders)
                ->description('All your orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            
            Stat::make('Pending Orders', $pendingOrders)
                ->description('Orders in progress')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('Completed Orders', $completedOrders)
                ->description('Finished orders')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('Total Paid', 'Rp ' . number_format($totalPaid, 0, ',', '.'))
                ->description('Total amount paid')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            
            Stat::make('Total Payments', $totalPayments)
                ->description('Number of payments made')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('info'),
            
            Stat::make('Last Payment', $lastPayment ? $lastPayment->payment_date->format('M d, Y') : 'No payments yet')
                ->description($lastPayment ? 'Rp ' . number_format($lastPayment->amount, 0, ',', '.') : 'Make your first payment')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($lastPayment ? 'success' : 'gray'),
        ];
    }
}