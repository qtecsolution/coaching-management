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
}
