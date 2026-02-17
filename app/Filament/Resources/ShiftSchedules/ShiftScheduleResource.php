<?php

namespace App\Filament\Resources\ShiftSchedules;

use App\Filament\Resources\ShiftSchedules\Pages\CreateShiftSchedule;
use App\Filament\Resources\ShiftSchedules\Pages\EditShiftSchedule;
use App\Filament\Resources\ShiftSchedules\Pages\ListShiftSchedules;
use App\Filament\Resources\ShiftSchedules\Pages\ViewShiftSchedule;
use App\Filament\Resources\ShiftSchedules\Schemas\ShiftScheduleForm;
use App\Filament\Resources\ShiftSchedules\Schemas\ShiftScheduleInfolist;
use App\Filament\Resources\ShiftSchedules\Tables\ShiftSchedulesTable;
use App\Models\ShiftSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ShiftScheduleResource extends Resource
{
    protected static ?string $model = ShiftSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;
    protected static string|UnitEnum|null $navigationGroup = 'Shift';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ShiftScheduleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShiftScheduleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShiftSchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShiftSchedules::route('/'),
            'create' => CreateShiftSchedule::route('/create'),
            'view' => ViewShiftSchedule::route('/{record}'),
            'edit' => EditShiftSchedule::route('/{record}/edit'),
        ];
    }
}
