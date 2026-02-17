<?php

namespace App\Filament\Resources\EmployeeHistories\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmployeeHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('type')
                    ->required(),
                DatePicker::make('effective_date')
                    ->required(),
                TextInput::make('old_data'),
                TextInput::make('new_data'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
