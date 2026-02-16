<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $it = Department::create(['name' => 'IT Department']);
        Department::create(['name' => 'Software Engineer', 'parent_id' => $it->id]);
        Department::create(['name' => 'HR & Finance']);
        Department::create(['name' => 'Marketing']);
    }
}
