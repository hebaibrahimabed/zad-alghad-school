<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'min_age',
        'max_age',
        'is_active',
    ];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'level_id');
    }
}
