<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'parent_id'];

    // Ambil Divisi Atasannya
    public function parent() {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    // Ambil Sub-Departemen di bawahnya
    public function children() {
        return $this->hasMany(Department::class, 'parent_id');
    }
}
