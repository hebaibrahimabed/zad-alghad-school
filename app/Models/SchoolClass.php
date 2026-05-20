<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'level_id',
        'name',
        'academic_year',
        'price',
        'start_date',
        'end_date',
        'min_capacity',
        'max_capacity',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'price'      => 'decimal:2',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'class_id');
    }

    // Accessors
    public function getStudentsCountAttribute(): int
    {
        return $this->registrations()->count();
    }

    public function getIsFullAttribute(): bool
    {
        if (!$this->max_capacity) return false;
        return $this->students_count >= $this->max_capacity;
    }
}
