<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::create(['name' => 'Cuti Tahunan', 'default_quota' => 12]);
        LeaveType::create(['name' => 'Cuti Sakit', 'default_quota' => 30]);
        LeaveType::create(['name' => 'Cuti Menikah', 'default_quota' => 3]);
    }
}
