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
        SalaryComponent::create(['name' => 'Gaji Pokok', 'type' => 'allowance', 'is_fixed' => true]);
        SalaryComponent::create(['name' => 'Tunjangan Transport', 'type' => 'allowance', 'is_fixed' => true]);
        SalaryComponent::create(['name' => 'BPJS Kesehatan', 'type' => 'deduction', 'is_fixed' => true]);
        SalaryComponent::create(['name' => 'Bonus Project', 'type' => 'allowance', 'is_fixed' => false]);
    }
}
