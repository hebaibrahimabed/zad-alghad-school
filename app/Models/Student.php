<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'IDNumber';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'IDNumber',
        'studentName',
        'FatherName',
        'GrandfatherName',
        'lastName',
        'dateOfBirth',
        'gender',
        'gradeByAge',
        'lastCertificateObtained',
        'Parentmobile',
        'RelativeGuardian',
        'healthCondition',
        'OrphanStatus',
        'registrationDate',
        'paymentStatus',
        'RegistrationStatusMinistry',
    ];
    protected $casts = [
        'dateOfBirth' => 'datetime',
        'registrationDate' => 'datetime',
    ];

    // Validation Rules
    public static function validationRules($id = null)
    {
        return [
            'IDNumber' => 'required|string||digits:9|unique:students,IDNumber,' . $id . ',IDNumber',
            'studentName' => 'required|string|max:20',
            'FatherName' => 'required|string|max:20',
            'GrandfatherName' => 'nullable|string|max:20',
            'lastName' => 'required|string|max:20',
            'dateOfBirth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'gradeByAge' => 'nullable|string|max:20',
            'lastCertificateObtained' => 'nullable|string|max:20',
            'Parentmobile' => 'required|string|regex:/^[0-9+]{10,15}$/',
            'RelativeGuardian' => 'nullable|string|max:20',
            'healthCondition' => 'required|in:Healthy,disabled,injured',
            'OrphanStatus' => 'required|in:orphan,not orphan',
            'registrationDate' => 'required|date|before_or_equal:today',
            'paymentStatus' => 'nullable|string|max:50',
            'RegistrationStatusMinistry' => 'nullable|string|max:50',
        ];
    }

    // Custom validation messages
    public static function validationMessages()
    {
        return [
            'IDNumber.required' => 'رقم الهوية مطلوب',
            'IDNumber.unique' => 'رقم الهوية موجود مسبقاً',
            'studentName.required' => 'اسم الطالب مطلوب',
            'FatherName.required' => 'اسم الأب مطلوب',
            'lastName.required' => 'اسم العائلة مطلوب',
            'dateOfBirth.required' => 'تاريخ الميلاد مطلوب',
            'dateOfBirth.before' => 'تاريخ الميلاد يجب أن يكون في الماضي',
            'gender.required' => 'الجنس مطلوب',
            'gender.in' => 'قيمة الجنس غير صحيحة',
            'Parentmobile.required' => 'رقم هاتف ولي الأمر مطلوب',
            'Parentmobile.regex' => 'صيغة رقم الهاتف غير صحيحة',
            'healthCondition.required' => 'الحالة الصحية مطلوبة',
            'healthCondition.in' => 'قيمة الحالة الصحية غير صحيحة',
            'OrphanStatus.required' => 'حالة اليتم مطلوبة',
            'OrphanStatus.in' => 'قيمة حالة اليتم غير صحيحة',
            'registrationDate.required' => 'تاريخ التسجيل مطلوب',
            'registrationDate.before_or_equal' => 'تاريخ التسجيل لا يمكن أن يكون في المستقبل',
        ];
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->studentName . ' ' . $this->FatherName . ' ' .
               ($this->GrandfatherName ? $this->GrandfatherName . ' ' : '') .
               $this->lastName;
    }

    // Accessor for age
    public function getAgeAttribute()
    {
        return $this->dateOfBirth ? $this->dateOfBirth->age : null;
    }

}
