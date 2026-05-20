<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'type',
        'value',
        'value_type',
        'start_date',
        'end_date',
        'is_active',
        'notes',
    ];

    public function studentDiscounts()
    {
        return $this->hasMany(StudentDiscount::class, 'discount_id');
    }
}
