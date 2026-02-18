<?php

namespace App\Filament\Resources\ShiftSchedules\Schemas;

use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ShiftScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee.name'),
                TextEntry::make('shift.name'),
                TextEntry::make('schedule_date')
                    ->date(),
                TextEntry::make('Duration')
                    ->label('Durasi')
                    ->getStateUsing(function ($record) {
                        $start = Carbon::parse($record->shift->start_time)->format('H:i');
                        $end = Carbon::parse($record->shift->end_time)->format('H:i');

                        return "{$start} - {$end}";
                    }),
                // TextEntry::make('total_hours')
                //     ->label('Total Durasi')
                //     ->getStateUsing(function ($record) {
                //         $start = Carbon::parse($record->shift->start_time);
                //         $end = Carbon::parse($record->shift->end_time);

                //         // Handle shift malam
                //         if ($end->lt($start)) {
                //             $end->addDay();
                //         }

                //         return $start->diffInHours($end).' Jam';
                //     }),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
