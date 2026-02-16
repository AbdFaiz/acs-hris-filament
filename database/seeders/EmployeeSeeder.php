<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'employee@acs.com')->first();

        $emp = Employee::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'employee_code' => 'DUM-001',
            'name' => 'Employee Dummy',
            'phone' => '081234567890',
            'address' => 'Jl. Dummy No. 123',
            'birth_date' => Date('1990-01-01'),
            'gender' => 'L',
            'marital_status' => 'single',
            'join_date' => Date('2026-02-01'),
            'status' => 'contract',
            'department_id' => 1,
            'position_id' => 1,
            'manager_id' => null,
            'is_active' => true
        ]);

        // Berikan saldo cuti default dari LeaveType
        $leaveTypes = LeaveType::all();
        foreach ($leaveTypes as $type) {
            LeaveBalance::create([
                'employee_id' => $emp->id,
                'leave_type_id' => $type->id,
                'year' => 2026,
                'total_quota' => $type->default_quota,
                'used' => 0
            ]);
        }
    }
}
