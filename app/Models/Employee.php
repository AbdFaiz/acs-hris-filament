<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'is_active' => 'boolean', // Memastikan data 0/1 jadi true/false di aplikasi
    ];

    protected $with = [
        'department',
        'position',
        'manager',
    ];

    protected $appends = ['full_info'];

    // Custom Accessors
    // NIK - Nama
    public function getFullInfoAttribute()
    {
        return "{$this->nik} - {$this->name}";
    }

    // Relasi ke User (Login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Departemen
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relasi ke Jabatan
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // Relasi ke Atasan (Self-reference)
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    // Relasi ke Absensi
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Relasi ke Slip Gaji
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    // Relasi ke Komponen Gaji Karyawan
    public function salaryComponents()
    {
        return $this->belongsToMany(SalaryComponent::class, 'employee_salary_components')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function histories()
    {
        return $this->hasMany(EmployeeHistory::class);
    }

    protected static function booted()
    {
        // Mapping untuk kolom ID ke Nama Model-nya
        $idMappings = [
            'department_id' => Department::class,
            'position_id' => Position::class,
            'manager_id' => Employee::class,
        ];

        // Fungsi Helper internal untuk ambil Nama dari ID
        $getNameFromId = function ($key, $id) use ($idMappings) {
            if (! $id || ! isset($idMappings[$key])) {
                return $id;
            }
            $model = $idMappings[$key];

            // Kita asumsikan semua model punya kolom 'name', khusus manager mungkin butuh logic lain
            return $model::find($id)?->name ?? "ID: $id";
        };

        static::created(function ($employee) {
            EmployeeHistory::create([
                'employee_id' => $employee->id,
                'type' => 'Registration',
                'effective_date' => $employee->join_date ?? now(),
                'old_data' => null,
                'new_data' => $employee->getAttributes(),
                'description' => "Karyawan baru berhasil didaftarkan dengan NIK {$employee->nik}.",
            ]);
        });

        static::updated(function ($employee) use ($getNameFromId, $idMappings) {
            $changes = $employee->getChanges();
            unset($changes['updated_at']);

            if (! empty($changes)) {
                $oldData = [];
                $newData = [];
                $changedFields = [];

                foreach ($changes as $key => $newValue) {
                    $oldValue = $employee->getOriginal($key);

                    // Jika kolomnya adalah ID (seperti position_id), kita simpan Namanya
                    if (isset($idMappings[$key])) {
                        $readableKey = str_replace('_id', '', $key);
                        $oldData[$readableKey] = $getNameFromId($key, $oldValue);
                        $newData[$readableKey] = $getNameFromId($key, $newValue);
                        $changedFields[] = ucfirst($readableKey);
                    } else {
                        $oldData[$key] = $oldValue;
                        $newData[$key] = $newValue;
                        $changedFields[] = ucfirst(str_replace('_', ' ', $key));
                    }
                }

                // Tentukan Type secara dinamis
                $type = 'Update Profile';
                if (array_key_exists('position_id', $changes)) {
                    $type = 'Promotion/Demotion';
                }
                if (array_key_exists('department_id', $changes)) {
                    $type = 'Transfer';
                }
                if (array_key_exists('is_active', $changes)) {
                    $type = 'Status Change';
                }

                EmployeeHistory::create([
                    'employee_id' => $employee->id,
                    'type' => $type,
                    'effective_date' => now(),
                    'old_data' => $oldData,
                    'new_data' => $newData,
                    'description' => 'Perubahan pada: '.implode(', ', $changedFields).' oleh '.(auth()->user()->name ?? 'System'),
                ]);
            }
        });
    }
}
