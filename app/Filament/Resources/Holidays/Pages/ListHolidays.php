<?php

namespace App\Filament\Resources\Holidays\Pages;

use App\Filament\Resources\Holidays\HolidayResource;
use App\Models\Holiday;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Http;

class ListHolidays extends ListRecords
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncApi')
                ->label('Sync API')
                ->icon('heroicon-m-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    $response = Http::get('https://libur.deno.dev/api');

                    if ($response->successful()) {
                        $holidays = $response->json();
                        $count = 0;

                        foreach ($holidays as $data) {
                            // UpdateOrCreate agar tidak duplikat jika diklik berkali-kali
                            Holiday::updateOrCreate(
                                ['date' => $data['date']],
                                [
                                    'name' => $data['name'],
                                    'is_national' => true,
                                ]
                            );
                            $count++;
                        }

                        Notification::make()
                            ->title('Sync Berhasil')
                            ->body("$count data hari libur telah diperbarui.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Sync Gagal')
                            ->danger()
                            ->send();
                    }
                }),
            CreateAction::make()->label('Tambah Manual'),
        ];
    }
}
