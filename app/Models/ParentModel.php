<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Student;

class ParentModel extends Model
{
    use SoftDeletes;

    protected $table = 'parents';

    protected $fillable = [
        'first_name',
        'second_name',
        'third_name',
        'gender',
        'birth_date',
        'phone',
        'national_id',
        'relation',
        'address',
        'housing_status',
        'work',
        'orphan_status_student',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
