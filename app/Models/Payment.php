<?php

namespace App\Models;

use App\Enums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_transaction_no',
        'name',
        'type',
        'status',
        'amount_to_pay'
    ];

    protected $casts = [
        'name' => Enums\PaymentName::class,
        'type' => Enums\PaymentType::class,
        'status' => Enums\PaymentStatus::class,
        'amount_to_pay' => 'integer'
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_transaction_no', 'transaction_no');
    }
}
