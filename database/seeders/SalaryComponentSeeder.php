<?php

namespace Database\Seeders;

use App\Models\SalaryComponent;
use Illuminate\Database\Seeder;

class SalaryComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalaryComponent::create(['name' => 'Gaji Pokok', 'code' => 'BASIC', 'type' => 'allowance', 'is_fixed' => true]);
        SalaryComponent::create(['name' => 'Tunjangan Transport', 'code' => 'TRANSPORT', 'type' => 'allowance', 'is_fixed' => true]);
        SalaryComponent::create(['name' => 'BPJS Kesehatan', 'code' => 'BPJS-KES', 'type' => 'deduction', 'is_fixed' => true]);
        SalaryComponent::create(['name' => 'Bonus Project', 'code' => 'BONUS-PRJ', 'type' => 'allowance', 'is_fixed' => false]);
    }
}
