<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;

class HistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';

    protected static ?string $title = 'Log Perubahan & Riwayat';

    public function form(Schema $schema): Schema
    {
        // Kosongkan karena history dicatat otomatis oleh Model Booted
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu Update')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', strtoupper($state)))
                    ->color(fn (string $state): string => match ($state) {
                        'update_profile' => 'info',
                        'promotion' => 'success',
                        'salary_change' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->wrap(),

                // Menampilkan summary perubahan data
                TextColumn::make('changes')
                    ->label('Detail Perubahan')
                    ->getStateUsing(function ($record) {
                        $output = [];
                        if (!$record->new_data) return '-';

                        foreach ($record->new_data as $key => $newValue) {
                            $oldValue = $record->old_data[$key] ?? 'N/A';
                            // Format: Nama Kolom: Dari -> Menjadi
                            $label = ucfirst(str_replace('_', ' ', $key));
                            $output[] = "{$label}: {$oldValue} ➔ {$newValue}";
                        }
                        return implode(' | ', $output);
                    })
                    ->wrap()
                    ->color('gray')
                    ->size('sm'),
            ])
            ->filters([
                // Tambahkan filter jika perlu
            ])
            ->headerActions([]) // Matikan tombol Create
            ->actions([])       // Matikan tombol Edit/Delete
            ->bulkActions([]);
    }
}
