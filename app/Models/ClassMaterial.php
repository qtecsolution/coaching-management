<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassMaterial extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function batch_day()
    {
        return $this->belongsTo(BatchDay::class);
    }
}
