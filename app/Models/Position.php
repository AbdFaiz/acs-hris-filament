<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'level', 'description'];

    // Menarik semua karyawan yang punya jabatan ini
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    // Menarik master KPI yang dikhususkan untuk jabatan ini
    // public function kpis()
    // {
    //     return $this->hasMany(Kpi::class);
    // }
}
