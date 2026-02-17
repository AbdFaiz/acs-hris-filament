<?php

namespace App\Filament\Resources\EmployeeHistories\Pages;

use App\Filament\Resources\EmployeeHistories\EmployeeHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeHistories extends ListRecords
{
    protected static string $resource = EmployeeHistoryResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         CreateAction::make(),
    //     ];
    // }
}
