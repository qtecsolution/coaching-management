<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['name','phone'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function getNameAttribute() {
        return $this->user ? $this->user->name : null;
    }
    public function getPhoneAttribute()
    {
        return $this->user ? $this->user->phone : null;
    }
}
