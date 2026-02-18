<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
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
            'birth_date' => date('1990-01-01'),
            'gender' => 'L',
            'marital_status' => 'single',
            'join_date' => date('2026-02-01'),
            'status' => 'contract',
            'department_id' => 1,
            'position_id' => 1,
            'manager_id' => null,
            'is_active' => true,
            'basic_salary' => 5000000
        ]);

        // Berikan saldo cuti default dari LeaveType
        $leaveTypes = LeaveType::all();
        foreach ($leaveTypes as $type) {
            LeaveBalance::create([
                'employee_id' => $emp->id,
                'leave_type_id' => $type->id,
                'year' => 2026,
                'total_quota' => $type->default_quota,
                'used' => 0,
            ]);
        }

        // User 2
        $userFaiz = User::where('email', 'faiz@acs.com')->first();

        $empFaiz = Employee::create([
            'user_id' => $userFaiz->id,
            'nik' => '9876543210987654',
            'employee_code' => 'IT-009',
            'name' => 'Faiz',
            'phone' => '081298765432',
            'address' => 'Jl. ACS No. 1',
            'birth_date' => '1998-05-10',
            'gender' => 'L',
            'marital_status' => 'single',
            'join_date' => '2026-02-01',
            'status' => 'permanent',
            'department_id' => 1,
            'position_id' => 1,
            'manager_id' => null,
            'is_active' => true,
            'basic_salary' => 4500000
        ]);

        foreach ($leaveTypes as $type) {
            LeaveBalance::create([
                'employee_id' => $empFaiz->id,
                'leave_type_id' => $type->id,
                'year' => 2026,
                'total_quota' => $type->default_quota,
                'used' => 0,
            ]);
        }
    }
}
