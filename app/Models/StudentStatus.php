<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentStatus extends Model
{
    protected $fillable = [
        'registration_id',
        'status',
        'status_date',
        'notes',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
