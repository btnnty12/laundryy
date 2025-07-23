<?php

namespace App\Filament\Admin\Resources\OrderHistoryResource\Pages;

use App\Filament\Admin\Resources\OrderHistoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrderHistory extends CreateRecord
{
    protected static string $resource = OrderHistoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['changed_by'] = Auth::id();
        
        return $data;
    }
}