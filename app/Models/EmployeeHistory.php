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
        'new_data' => 'array'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
