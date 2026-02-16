<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Buat user dulu
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['user_email'],
            'password' => Hash::make($data['user_password']),
            'email_verified_at' => now(),
        ]);

        // 2. Assign role (Spatie)
        $user->assignRole($data['user_role']);

        // 3. Inject user_id ke employee
        $data['user_id'] = $user->id;

        // 4. Buang field palsu biar tidak ikut insert
        unset(
            $data['user_email'],
            $data['user_password'],
            $data['user_role'],
        );

        return $data;
    }
}
