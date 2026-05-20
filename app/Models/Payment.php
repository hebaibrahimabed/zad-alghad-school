<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_id',
        'amount_due_month',
        'total_outstanding',
        'amount_paid',
        'due_date',
        'paid_at',
        'payment_method',
        'status',
        'notes',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
