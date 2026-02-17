<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping
{
    protected $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function query()
    {
        // Gunakan query yang dikirim dari Filament
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Keterangan',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->employee->name,
            $attendance->date->format('d-m-Y'),
            $attendance->check_in,
            $attendance->check_out,
            ucfirst($attendance->status),
            $attendance->note,
        ];
    }
}
