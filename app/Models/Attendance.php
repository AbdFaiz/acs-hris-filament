<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Hitung apakah telat (Asumsi masuk jam 08:00)
    public function getIsLateAttribute()
    {
        // Cari jadwal shift karyawan di tanggal ini
        $schedule = ShiftSchedule::where('employee_id', $this->employee_id)
            ->where('date', $this->date)
            ->first();

        // Jika tidak ada jadwal, asumsikan pakai jam reguler 08:00
        $startTime = $schedule ? $schedule->shift->start_time : '08:00:00';

        if (! $this->check_in) {
            return false;
        }

        return Carbon::parse($this->check_in)->gt(Carbon::parse($startTime));
    }

    // Hitung durasi kerja dalam jam
    public function getWorkDurationAttribute()
    {
        if (! $this->check_in || ! $this->check_out) {
            return 0;
        }
        $in = Carbon::parse($this->check_in);
        $out = Carbon::parse($this->check_out);

        return round($in->diffInMinutes($out) / 60, 2);
    }
}
