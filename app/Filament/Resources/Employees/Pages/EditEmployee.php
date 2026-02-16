<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->getRecord()->user;

        if ($user) {
            $data['user_email'] = $user->email;
            $data['user_role'] = $user->roles->first()?->name;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            // 1. Update data User terkait
            $userData = [
                'name' => $data['name'],
                'email' => $data['user_email'],
            ];

            // Hanya update password jika diisi di form
            if (!empty($data['user_password'])) {
                $userData['password'] = $data['user_password'];
            }

            $record->user->update($userData);

            // 2. Update Role (Spatie)
            if (!empty($data['user_role'])) {
                $record->user->syncRoles([$data['user_role']]);
            }

            // 3. Bersihkan data temporary sebelum simpan ke tabel employees
            unset($data['user_email'], $data['user_password'], $data['user_role']);

            // 4. Update data Employee
            $record->update($data);

            return $record;
        });
    }
}
