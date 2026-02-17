<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use App\Models\LeaveBalance;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use App\Models\LeaveType;
use Carbon\Carbon;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Employee Selection
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->default(auth()->user()->employee?->id) // Set default ke dirinya sendiri
                    ->disabled(fn () => !auth()->user()->hasRole(['hr', 'admin'])) // Matikan jika bukan HRD
                    ->dehydrated() // Pastikan tetap terkirim ke database saat save
                    ->required(),

                Select::make('leave_type_id')
                    ->label('Tipe Cuti')
                    ->relationship('leaveType', 'name')
                    ->required()
                    ->live(),

                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::calculateTotalDays($set, $get)),

                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::calculateTotalDays($set, $get)),

                TextInput::make('total_days')
    ->label('Total Hari')
    ->numeric()
    ->readOnly()
    ->required()
    ->live()
    ->rules([
        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
            $empId = $get('employee_id');
            $typeId = $get('leave_type_id');
            $year = $get('start_date') ? date('Y', strtotime($get('start_date'))) : date('Y');

            if (!$empId || !$typeId) return;

            $balance = LeaveBalance::where('employee_id', $empId)
                ->where('leave_type_id', $typeId)
                ->where('year', $year)
                ->first();

            if (!$balance) {
                $fail("Anda tidak memiliki jatah cuti untuk tipe ini di tahun $year.");
                return;
            }

            $sisaCuti = $balance->total_quota - $balance->used;

            if ($value > $sisaCuti) {
                $fail("Jatah cuti tidak mencukupi. Sisa jatah: $sisaCuti hari.");
            }
        },
    ]),

                FileUpload::make('attachment')
                    ->label('Lampiran (Opsional)')
                    ->directory('leave-attachments')
                    ->visibility('private'),

                Textarea::make('reason')
                    ->label('Alasan Cuti')
                    ->required()
                    ->columnSpanFull(),

                // Status & Approved By hanya muncul/bisa diedit oleh HRD
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected'
                    ])
                    ->default('pending')
                    ->visible(fn () => auth()->user()->hasRole('hrd')) // Hanya HRD yang lihat
                    ->required(),
            ]);
    }

    // Logic hitung hari otomatis
    public static function calculateTotalDays($set, $get)
    {
        $start = $get('start_date');
        $end = $get('end_date');

        if ($start && $end) {
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            // Hitung selisih hari termasuk hari pertama
            $set('total_days', $startDate->diffInDays($endDate) + 1);
        }
    }
}
