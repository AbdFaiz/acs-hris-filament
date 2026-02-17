<?php

namespace App\Filament\Resources\EmployeeHistories\Pages;

use App\Filament\Resources\EmployeeHistories\EmployeeHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeHistory extends EditRecord
{
    protected static string $resource = EmployeeHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
