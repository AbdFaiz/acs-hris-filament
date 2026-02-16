<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            ['date' => '2026-01-01', 'name' => 'Tahun Baru 2026 Masehi'],
            ['date' => '2026-02-17', 'name' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['date' => '2026-03-20', 'name' => 'Hari Suci Nyepi'],
            ['date' => '2026-03-20', 'name' => 'Hari Raya Idul Fitri 1447 H'],
            ['date' => '2026-03-21', 'name' => 'Hari Raya Idul Fitri 1447 H'],
            ['date' => '2026-05-01', 'name' => 'Hari Buruh Internasional'],
            ['date' => '2026-05-14', 'name' => 'Kenaikan Yesus Kristus'],
            ['date' => '2026-06-01', 'name' => 'Hari Lahir Pancasila'],
            ['date' => '2026-08-17', 'name' => 'Hari Kemerdekaan RI'],
            ['date' => '2026-12-25', 'name' => 'Hari Raya Natal'],
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}
