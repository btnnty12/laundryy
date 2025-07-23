<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\OrderHistory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Check if status was changed
        if ($this->record->wasChanged('status')) {
            OrderHistory::create([
                'order_id' => $this->record->id,
                'status' => $this->record->status,
                'notes' => 'Status changed to ' . $this->record->status,
                'changed_by' => Auth::id(),
            ]);
        }
    }
}