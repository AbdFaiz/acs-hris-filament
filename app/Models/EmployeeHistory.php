<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'effective_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTitleAttribute()
    {
        return $this->employee
            ? "{$this->employee->nik} - {$this->employee->name} ({$this->type})"
            : '-';
    }
}
