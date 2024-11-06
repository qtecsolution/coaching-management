<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchDay extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['day_name', 'teacher_name', 'subject_name'];

    public function getDayNameAttribute()
    {
        switch ($this->attributes['day']) {
            case 1:
                return 'Saturday';
            case 2:
                return 'Sunday';
            case 3:
                return 'Monday';
            case 4:
                return 'Tuesday';
            case 5:
                return 'Wednesday';
            case 6:
                return 'Thursday';
            case 7:
                return 'Friday';
            default:
                return 'Unknown Day';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTeacherNameAttribute()
    {
        return $this->user ? $this->user->name : '';
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getSubjectNameAttribute()
    {
        return $this->subject ? $this->subject->name : '';
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
