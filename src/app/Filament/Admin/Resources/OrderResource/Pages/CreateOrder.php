<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\OrderHistory;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Create order history entry
        OrderHistory::create([
            'order_id' => $this->record->id,
            'status' => $this->record->status,
            'notes' => 'Order created',
            'changed_by' => Auth::id(),
        ]);
    }
}