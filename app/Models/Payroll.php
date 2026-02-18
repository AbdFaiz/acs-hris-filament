<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'basic_salary' => 'float',
        'total_allowance' => 'float',
        'total_deduction' => 'float',
        'net_salary' => 'float',
        'payment_date' => 'date'
    ];

    // Menghitung otomatis sisa gaji di level model
    public function getCalculatedNetSalaryAttribute()
    {
        return $this->basic_salary + $this->total_allowance - $this->total_deduction;
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    // Relasi ke rincian item (Tunjangan/Potongan)
    public function items() {
        return $this->hasMany(PayrollItem::class);
    }

    public function salaryComponents()
    {
        return $this->belongsToMany(SalaryComponent::class, 'payroll_items')
                    ->withPivot(['amount', 'type', 'name']);
    }
}
