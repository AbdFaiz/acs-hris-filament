<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('check_in')
                    ->time('H:i')
                    ->color(fn ($record) => $record->is_late ? 'danger' : 'success')
                    ->description(fn ($record) => $record->is_late ? 'Telat' : 'Tepat Waktu'),
                TextColumn::make('check_out')
                    ->time('H:i'),
                TextColumn::make('work_duration')
                    ->label('Durasi (Jam)')
                    ->suffix(' Jam'),
                SelectColumn::make('status')
                    ->options([
                        'present' => 'Hadir',
                        'late' => 'Telat',
                        'absent' => 'Alpa',
                        'on_leave' => 'Cuti',
                    ]),
            ])
            ->filters([
                TrashedFilter::make(),
                Filter::make('today')
                    ->label('Hari ini')
                    ->query(fn (Builder $query): Builder => $query->whereDate('date', now()->today()))
                    ->default(),
                SelectFilter::make('status')
                    ->options([
                        'present' => 'Hadir',
                        'late' => 'Telat',
                        'absent' => 'Alpa',
                        'on_leave' => 'Cuti',
                    ])
                    ->label('Status Kehadiran'),
                Filter::make('date')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('date', '<=', $data['until']));
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
