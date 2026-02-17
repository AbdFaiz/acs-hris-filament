<?php

namespace App\Filament\Resources\LeaveBalances\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeaveBalanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('leave_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('year')
                    ->required(),
                TextInput::make('total_quota')
                    ->required()
                    ->numeric(),
                TextInput::make('used')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
