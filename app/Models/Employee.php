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

    protected static function booted()
    {
        static::updated(function ($employee) {
            // Ambil data yang benar-benar berubah saja
            $changes = $employee->getChanges();

            // Abaikan kolom yang tidak penting dicatat historinya
            unset($changes['updated_at']);

            if (! empty($changes)) {
                $oldData = [];
                foreach ($changes as $key => $newValue) {
                    $oldData[$key] = $employee->getOriginal($key);
                }

                \App\Models\EmployeeHistory::create([
                    'employee_id' => $employee->id,
                    'type' => 'update_profile',
                    'old_data' => $oldData,
                    'new_data' => $changes,
                    'description' => 'Perubahan data oleh '.(auth()->user()->name ?? 'System'),
                ]);
            }
        });
    }
}
