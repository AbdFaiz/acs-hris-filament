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
        'basic_salary' => 'decimal',
        'total_allowance' => 'decimal',
        'total_deduction' => 'decimal',
        'net_salary' => 'decimal',
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
}
