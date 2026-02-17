<?php

namespace App\Filament\Resources\EmployeeHistories\Pages;

use App\Filament\Resources\EmployeeHistories\EmployeeHistoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployeeHistory extends ViewRecord
{
    protected static string $resource = EmployeeHistoryResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         EditAction::make(),
    //     ];
    // }
}
