<?php

namespace App\Filament\Resources\ShiftSchedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShiftScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->multiple()
                    ->searchable()
                    ->required(),
                Select::make('shift_id')
                    ->relationship('shift', 'name')
                    ->required(),
                DatePicker::make('schedule_date')
                    ->required()
                    ->default(now()),
            ]);
    }
}
