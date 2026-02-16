<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'default_quota'];

    // Relasi ke transaksi pengajuan cuti
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // Relasi ke saldo jatah cuti karyawan
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }
}
