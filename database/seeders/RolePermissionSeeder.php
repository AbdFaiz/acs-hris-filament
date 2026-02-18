<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Employee & History
            'view-employees', 'create-employees', 'edit-employees', 'delete-employees',
            'view-employee-history',

            // Payroll & Components
            'view-payroll', 'manage-payroll', 'manage-salary-components',

            // Attendance & Shifts
            'view-attendance', 'manage-attendance',
            'manage-shifts', 'manage-schedules',

            // Leave Management
            'view-leave-requests', 'approve-leave', 'manage-leave-types',

            // Organization Master
            'manage-departments', 'manage-positions', 'manage-holidays',

            // Documents
            'view-all-documents', 'manage-documents',

            // ESS (Employee Self Service)
            'ess-access',

            // Filament Access (Penting agar bisa masuk ke admin panel)
            'access-admin-panel',
        ];

        foreach ($permissions as $permission) {
            // Tambahkan guard_name agar konsisten
            Permission::updateOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Buat Roles
        $roleAdmin = Role::updateOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleHR = Role::updateOrCreate(['name' => 'hr', 'guard_name' => 'web']);
        $roleHR->givePermissionTo([
            'access-admin-panel', // HR boleh masuk panel
            'view-employees', 'create-employees', 'edit-employees',
            'view-employee-history',
            'manage-payroll', 'manage-salary-components',
            'manage-attendance', 'manage-schedules',
            'view-leave-requests', 'approve-leave',
            'manage-departments', 'manage-positions', 'manage-holidays',
            'view-all-documents', 'manage-documents',
        ]);

        $roleEmployee = Role::updateOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        $roleEmployee->givePermissionTo(['ess-access', 'view-attendance']);

        // 3. Buat User (Gunakan updateOrCreate agar seeder bisa dijalankan berkali-kali tanpa error)
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@acs.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin'), // Gunakan Hash::make lebih clean
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole($roleAdmin);

        $employeeUser = User::updateOrCreate(
            ['email' => 'employee@acs.com'],
            [
                'name' => 'Regular Employee',
                'password' => Hash::make('employee'),
                'email_verified_at' => now(),
            ]
        );
        $employeeUser->assignRole($roleEmployee);

        $faizUser = User::firstOrCreate(
            ['email' => 'faiz@acs.com'],   // kondisi pencarian
            [
                'name' => 'Faiz',
                'password' => Hash::make('faiz'),
                'email_verified_at' => now(),
            ]
        );

        $faizUser->assignRole($roleEmployee);
    }
}
