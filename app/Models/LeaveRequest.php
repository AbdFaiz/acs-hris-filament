<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    // Siapa yang approve (User HR/Manager)
    public function approver() {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
