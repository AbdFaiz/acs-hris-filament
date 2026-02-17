<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Exports\AttendanceExport;
use App\Filament\Resources\Attendances\AttendanceResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    // Ambil query tabel yang sudah difilter
                    $query = $this->getFilteredTableQuery();

                    return Excel::download(
                        new AttendanceExport($query),
                        'attendance-export-' . now()->format('Y-m-d') . '.xlsx'
                    );
            }),
            CreateAction::make(),
        ];
    }
}
