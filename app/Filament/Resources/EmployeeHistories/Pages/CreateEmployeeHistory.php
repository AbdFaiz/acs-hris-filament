<?php

namespace App\Filament\Resources\EmployeeHistories\Pages;

use App\Filament\Resources\EmployeeHistories\EmployeeHistoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeHistory extends CreateRecord
{
    protected static string $resource = EmployeeHistoryResource::class;
}
