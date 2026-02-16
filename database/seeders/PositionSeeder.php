<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::create(['name' => 'Manager', 'level' => 3]);
        Position::create(['name' => 'Senior Staff', 'level' => 2]);
        Position::create(['name' => 'Junior Staff', 'level' => 1]);
    }
}
