<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\URL;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Employee Documents';

    // Di v5, parameter form menggunakan Schema, bukan Form
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('document_type')
                    ->label('Tipe Dokumen')
                    ->options([
                        'ktp' => 'KTP',
                        'npwp' => 'NPWP',
                        'contract' => 'Kontrak Kerja',
                        'degree' => 'Ijazah',
                    ])
                    ->required(),

                FileUpload::make('file_path')
                    ->label('Berkas')
                    ->directory('employee-documents')
                    ->preserveFilenames()
                    ->openable()
                    ->downloadable()
                    ->required(),

                DatePicker::make('expired_at')
                    ->label('Masa Berlaku'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_type')
            ->columns([
                TextColumn::make('document_type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                TextColumn::make('file_path')
                    ->label('File')
                    ->icon('heroicon-m-paper-clip')
                    ->color('primary')
                    ->url(fn ($record) => URL::signedRoute('documents.download', $record),
                    shouldOpenInNewTab: true),

                TextColumn::make('expired_at')
                    ->label('Kadaluarsa')
                    ->date()
                    ->sortable()
                    ->color(fn ($state) =>
                        $state && now()->diffInDays($state, false) <= 30
                            ? 'danger'
                            : null
                    )
                    ->formatStateUsing(fn ($state) =>
                        $state ? $state->format('d M Y') : '-'
                    ),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Dokumen'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
