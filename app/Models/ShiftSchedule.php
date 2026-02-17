<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'schedule_date' => 'date',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function shift() {
        return $this->belongsTo(Shift::class);
    }
}
