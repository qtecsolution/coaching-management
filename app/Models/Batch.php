<?php

namespace App\Models;

use App\Http\Controllers\Admin\StudentController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    public static $statusList = [
        0 => 'Upcoming',
        1 => 'Running',
        2 => 'Completed'
    ];

    protected $guarded = [];

    public function batch_days()
    {
        return $this->hasMany(BatchDay::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function students()
    {
        return $this->hasMany(StudentBatch::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
