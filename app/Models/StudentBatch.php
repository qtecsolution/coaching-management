<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBatch extends Model
{
    protected $guarded = [];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function attendance()
    {
        return $this->hasMany(AttendanceRecord::class, 'student_id');
    }

    public function total_absent()
    {
        return $this->attendance()->where('status', 0)->count();
    }

    public function total_present()
    {
        return $this->attendance()->where('status', 1)->count();
    }

    public function total_late()
    {
        return $this->attendance()->where('status', 2)->count();
    }
}
