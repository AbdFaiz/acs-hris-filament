<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\User;
use Spatie\Permission\Models\Role; // Import Role Spatie
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECTION 1: AKUN (Full ke bawah)
                Section::make('Account Credentials')
                    ->description('Email, password, dan Role akses sistem.')
                    ->schema([
                        TextInput::make('user_email')
                            ->label('Email Login')
                            ->email()
                            ->required()
                            ->unique(
                                table: User::class,
                                column: 'email',
                                ignorable: fn ($record) => $record?->user // Abaikan user yang terelasi dengan employee ini
                            ),

                        TextInput::make('user_password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn ($context) => $context === 'create') // Wajib hanya saat buat baru
                            ->dehydrated(fn ($state) => filled($state)),

                        // TAMBAHAN: Pilih Role
                        Select::make('user_role')
                            ->label('System Role')
                            ->options(Role::all()->pluck('name', 'name')) // Ambil dari DB
                            ->default('employee')
                            ->required()
                            ->searchable(),
                    ]), // Tanpa .columns() agar satu kolom penuh ke bawah

                // SECTION 2: DATA PRIBADI
                Section::make('Personal Information')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('nik')->label('NIK')->required()->unique('employees', 'nik', ignoreRecord: true),
                        TextInput::make('employee_code')->required()->unique('employees', 'employee_code', ignoreRecord: true),

                        // Untuk input kecil, boleh pakai grid di DALAM section agar tidak terlalu panjang kebawah
                        Grid::make(2)->schema([
                            Select::make('gender')
                                ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])->required(),
                            Select::make('marital_status')
                                ->options(['single' => 'Single', 'married' => 'Married', 'divorced' => 'Divorced'])->required(),
                        ]),

                        DatePicker::make('birth_date'),
                        TextInput::make('phone')->tel(),
                        Textarea::make('address')->rows(3),
                    ]),

                // SECTION 3: DETAIL PEKERJAAN
                Section::make('Employment Details')
                    ->schema([
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->required()->searchable()->preload(),
                        Select::make('position_id')
                            ->relationship('position', 'name')
                            ->required()->searchable()->preload(),
                        Select::make('manager_id')
                            ->relationship('manager', 'name')
                            ->searchable()->preload(),

                        DatePicker::make('join_date')->required(),
                        Select::make('status')
                            ->options([
                                'permanent' => 'Permanent',
                                'contract' => 'Contract',
                                'internship' => 'Internship'
                            ])->required(),
                        Toggle::make('is_active')->label('Status Aktif')->onColor('success')->default(true),
                    ]),
            ]);
    }
}
