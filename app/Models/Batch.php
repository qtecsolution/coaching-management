<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function batch_days()
    {
        return $this->hasMany(BatchDay::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function students()  {
        return $this->hasMany(Student::class);
    }
}
