<?php

namespace App\Filament\Admin\Resources\OrderHistoryResource\Pages;

use App\Filament\Admin\Resources\OrderHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderHistory extends EditRecord
{
    protected static string $resource = OrderHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}