<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = ['name', 'type', 'is_fixed'];

    // Relasi ke item payroll (untuk tracking penggunaan komponen ini di slip gaji)
    public function payrollItems()
    {
        return $this->hasMany(PayrollItem::class);
    }

    // Relasi ke karyawan melalui pivot table employee_salary_components
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_salary_components')
                    ->withPivot('amount')
                    ->withTimestamps();
    }
}
