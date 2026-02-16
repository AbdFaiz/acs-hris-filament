<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function leaveType() {
        return $this->belongsTo(LeaveType::class);
    }
}
