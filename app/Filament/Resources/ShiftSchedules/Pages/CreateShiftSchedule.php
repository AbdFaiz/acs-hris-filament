<?php

namespace App\Filament\Resources\ShiftSchedules\Pages;

use App\Filament\Resources\ShiftSchedules\ShiftScheduleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateShiftSchedule extends CreateRecord
{
    protected static string $resource = ShiftScheduleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $employees = $data['employee_id']; // Isinya array ID [1, 2, 3]
        $shiftId = $data['shift_id'];
        $date = $data['schedule_date'];

        $lastRecord = null;

        foreach ($employees as $employeeId) {
            // Kita buat baris baru untuk setiap karyawan
            $lastRecord = static::getModel()::create([
                'employee_id'   => $employeeId,
                'shift_id'      => $shiftId,
                'schedule_date' => $date,
            ]);
        }

        // Return record terakhir agar Filament tidak error (redirect ke view/index)
        return $lastRecord;
    }
}
