<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchDayDate extends Model
{
    protected $guarded = [];

    protected $appends = ['day_name'];

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

    public function batchDay()
    {
        return $this->belongsTo(BatchDay::class);
    }

    public function attendance()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
