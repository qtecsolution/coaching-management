<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['name', 'phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batches()
    {
        return $this->hasMany(StudentBatch::class);
    }

    public function currentBatch()
    {
        return $this->hasOne(StudentBatch::class)->latest();
    }

    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : null;
    }

    public function getPhoneAttribute()
    {
        return $this->user ? $this->user->phone : null;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function dynamicFields()
    {
        return $this->hasMany(StudentDynamicField::class);
    }
}
