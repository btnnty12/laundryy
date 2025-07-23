<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Filament\Admin\Resources\PaymentResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Update order payment status if payment amount equals order total
        $order = $this->record->order;
        $totalPaid = $order->payments()->sum('amount');
        
        if ($totalPaid >= $order->total_price) {
            $order->update(['payment_status' => 'paid']);
        }
        // Note: For partial payments, status remains 'unpaid' as per enum constraints
    }
}