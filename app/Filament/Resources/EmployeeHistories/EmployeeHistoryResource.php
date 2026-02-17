<?php

namespace App\Filament\Resources\EmployeeHistories;

use App\Filament\Resources\EmployeeHistories\Pages\CreateEmployeeHistory;
use App\Filament\Resources\EmployeeHistories\Pages\EditEmployeeHistory;
use App\Filament\Resources\EmployeeHistories\Pages\ListEmployeeHistories;
use App\Filament\Resources\EmployeeHistories\Pages\ViewEmployeeHistory;
use App\Filament\Resources\EmployeeHistories\Schemas\EmployeeHistoryForm;
use App\Filament\Resources\EmployeeHistories\Schemas\EmployeeHistoryInfolist;
use App\Filament\Resources\EmployeeHistories\Tables\EmployeeHistoriesTable;
use App\Models\EmployeeHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EmployeeHistoryResource extends Resource
{
    protected static ?string $model = EmployeeHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;
    protected static string|UnitEnum|null $navigationGroup = 'Employee Management';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    // public static function form(Schema $schema): Schema
    // {
        // return EmployeeHistoryForm::configure($schema);
    // }

    public static function infolist(Schema $schema): Schema
    {
        return EmployeeHistoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeHistoriesTable::configure($table);
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
            'index' => ListEmployeeHistories::route('/'),
            // 'create' => CreateEmployeeHistory::route('/create'),
            'view' => ViewEmployeeHistory::route('/{record}'),
            // 'edit' => EditEmployeeHistory::route('/{record}/edit'),
        ];
    }
}
