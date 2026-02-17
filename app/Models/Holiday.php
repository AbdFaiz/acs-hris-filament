<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    // Mass assignment protection
    protected $fillable = [
        'date',
        'name',
        'is_national',
        'description'
    ];

    /**
     * Casting date agar otomatis menjadi object Carbon
     * Ini memudahkan saat kamu ingin cek $holiday->date->isWeekend()
     */
    protected $casts = [
        'date' => 'date',
        'is_nasional' => 'boolean'
    ];
}
