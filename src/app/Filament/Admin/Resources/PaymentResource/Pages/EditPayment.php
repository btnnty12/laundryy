<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Filament\Admin\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Update order payment status after payment is updated
        $order = $this->record->order;
        $totalPaid = $order->payments()->sum('amount');
        
        if ($totalPaid >= $order->total_price) {
            $order->update(['payment_status' => 'paid']);
        } else {
            $order->update(['payment_status' => 'unpaid']);
        }
        // Note: Using 'unpaid' for both no payment and partial payment as per enum constraints
    }
}