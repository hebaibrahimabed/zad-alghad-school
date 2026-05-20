<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDiscount extends Model
{
    protected $fillable = [
        'registration_id',
        'discount_id',
        'applied_value',
        'reason',
        'applied_by',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
