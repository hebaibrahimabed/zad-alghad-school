<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'class_id',
        'registration_date',
        'ministry_registration',
        'current_status',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function statuses()
    {
        return $this->hasMany(StudentStatus::class, 'registration_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'registration_id');
    }

    public function discounts()
    {
        return $this->hasMany(StudentDiscount::class, 'registration_id');
    }
}
