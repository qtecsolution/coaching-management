<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_batch_id',
        'amount',
        'month',
        'date',
        'payment_method',
        'transaction_id',
        'note',
        'status'
    ];

    protected $guarded = [];
    public function student_batch(){
        return $this->belongsTo(StudentBatch::class);
    }

    // Hook into model events
    protected static function boot()
    {
        parent::boot();

        // Update PaymentReport on payment creation
        static::created(function ($payment) {
            updatePaymentReport($payment->month);
        });

        // Update PaymentReport on payment update
        static::updated(function ($payment) {
            updatePaymentReport($payment->month);
        });
        // Update PaymentReport on payment delete
        static::deleted(function ($payment) {
            updatePaymentReport($payment->month);
        });
    }
}
