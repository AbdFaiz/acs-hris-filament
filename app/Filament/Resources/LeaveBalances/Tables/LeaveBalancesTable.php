<?php

namespace App\Filament\Resources\LeaveBalances\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\HeaderActions\CreateAction;
use Filament\Tables\Filters\SelectFilter;

class LeaveBalancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable()
                    // Hanya HR yang bisa lihat kolom nama karyawan (karena karyawan cuma liat punya sendiri)
                    ->visible(fn() => auth()->user()->hasRole(['hr', 'admin'])),

                TextColumn::make('leaveType.name')
                    ->label('Tipe Cuti')
                    ->sortable(),

                TextColumn::make('year')
                    ->label('Tahun'),

                TextColumn::make('total_quota')
                    ->label('Kuota')
                    ->numeric()
                    ->alignCenter(),

                TextColumn::make('used')
                    ->label('Terpakai')
                    ->numeric()
                    ->color('danger')
                    ->alignCenter(),

                // Kolom Sisa Jatah (Total - Used)
                TextColumn::make('remaining')
                    ->label('Sisa Jatah')
                    ->getStateUsing(fn ($record) => $record->total_quota - $record->used)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('year')
                    ->options([
                        date('Y') => date('Y'),
                        date('Y') + 1 => date('Y') + 1,
                    ])
                    ->default(date('Y')),
            ]);
    }
}
