<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public static $paymentMethods = [
        'Cash' => 'Cash',
        'Cheque' => 'Cheque',
        'Bank' => 'Bank',
        'bKash' => 'bKash',
        'Nagad' => 'Nagad',
        'Rocket' => 'Rocket',
        'Other' => 'Other'
    ];

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

    public function student_batch()
    {
        return $this->belongsTo(StudentBatch::class);
    }
    
    // Define the status map
    private const STATUS_MAP = [
        0 => ['class' => 'warning text-black', 'label' => 'Due'],
        1 => ['class' => 'success', 'label' => 'Paid'],
        2 => ['class' => 'primary', 'label' => 'Requested'],
        3 => ['class' => 'danger', 'label' => 'Failed']
    ];

    /**
     * Get the status badge HTML.
     *
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        $statusInfo = self::STATUS_MAP[$this->status] ?? ['class' => 'secondary', 'label' => 'Unknown'];

        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            $statusInfo['class'],
            $statusInfo['label']
        );
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_MAP[$this->status]['label'] ?? 'Unknown';
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
