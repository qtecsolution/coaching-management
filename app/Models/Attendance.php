<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
