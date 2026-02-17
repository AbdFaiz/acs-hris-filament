<?php

namespace App\Filament\Resources\LeaveRequests\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name') // Pakai relasi, jangan employee_id
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('leaveType.name') // Pakai relasi, jangan leave_type_id
                    ->label('Tipe Cuti')
                    ->sortable(),

                TextColumn::make('period')
                    ->label('Periode Cuti')
                    ->getStateUsing(function ($record) {
                        return $record->start_date->format('d M Y').' - '.$record->end_date->format('d M Y');
                    })
                    ->searchable(query: function ($query, string $search) {
                        $query->where('start_date', 'like', "%{$search}%")
                            ->orWhere('end_date', 'like', "%{$search}%");
                    }),

                TextColumn::make('total_days')
                    ->label('Durasi')
                    ->suffix(' Hari')
                    ->sortable(),

                TextColumn::make('reason')
                    ->label('Alasan')
                    ->wrap()
                    ->limit(100)
                    ->tooltip(fn ($record) => $record->reason),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('approver.name') // Menampilkan siapa yang approve
                    ->label('Disetujui Oleh')
                    ->placeholder('Belum diproses')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tgl Pengajuan')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tambahkan filter status jika perlu
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                // Tombol edit hanya muncul kalau masih pending? Bisa diatur di sini
                EditAction::make()
                    ->visible(fn ($record) => $record->status === 'pending' || auth()->user()->hasRole(['hr', 'admin'])),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
        // LOGIC VISIBILITAS TOMBOL
                    ->visible(function ($record) {
                        if ($record->status !== 'pending') {
                            return false;
                        }

                        $user = auth()->user();
                        $employee = $record->employee;

                        // 1. Jika saya adalah managernya si pemohon
                        if ($employee->manager_id === $user->employee?->id) {
                            return true;
                        }

                        // 2. Jika pemohon tidak punya manager, dan saya adalah HR
                        if ($employee->manager_id === null && $user->hasRole('hr')) {
                            return true;
                        }

                        return false;
                    })
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(), // Mencatat User ID yang klik approve
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Pengajuan Disetujui')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->visible(function ($record) {
                        if ($record->status !== 'pending') {
                            return false;
                        }

                        $user = auth()->user();
                        $employee = $record->employee;

                        // 1. Jika saya adalah managernya si pemohon
                        if ($employee->manager_id === $user->employee?->id) {
                            return true;
                        }

                        // 2. Jika pemohon tidak punya manager, dan saya adalah HR
                        if ($employee->manager_id === null && $user->hasRole('hr')) {
                            return true;
                        }

                        return false;
                    })
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending' &&
                    (auth()->user()->employee?->id === $record->employee->manager_id ||
                    ($record->employee->manager_id === null && auth()->user()->hasRole('hr')))
                    )
                    ->form([
                        \Filament\Forms\Components\Textarea::make('reason')->label('Alasan Penolakan')->required(),
                    ])
                    ->action(fn ($record) => $record->update(['status' => 'rejected'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
