<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ParentModel;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    // ① إزالة primaryKey القديم لأن id أصبح الـ PK
    protected $fillable = [
        'parent_id',        // ② إضافة parent_id
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
        'registrationDate',
        // ③ حذف: OrphanStatus, paymentStatus, RegistrationStatusMinistry
    ];

    protected $casts = [
        'dateOfBirth' => 'datetime',
        'registrationDate' => 'datetime',
    ];

    // Validation Rules
    public static function validationRules($id = null)
    {
        return [
            'parent_id' => 'nullable|exists:parents,id',   // ② إضافة
            'IDNumber' => 'required|string|digits:9|unique:students,IDNumber,' . $id,
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
            'registrationDate' => 'required|date|before_or_equal:today',
            // ③ حذف: OrphanStatus, paymentStatus, RegistrationStatusMinistry
        ];
    }

    // Custom validation messages
    public static function validationMessages()
    {
        return [
            'parent_id.exists' => 'ولي الأمر غير موجود',   // ② إضافة
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

    public static function determineGradeByAge($birthDate)
    {
        $birthDate = Carbon::parse($birthDate);
        $currentYear = now()->year;
        $cutoffDate = Carbon::create($currentYear, 1, 31);
        $age = $birthDate->diffInYears($cutoffDate);

        return match ($age) {
            3, 4 => 'بستان',
            5 => 'تمهيدي',
            6 => 'الأول',
            7 => 'الثاني',
            8 => 'الثالث',
            9 => 'الرابع',
            10 => 'الخامس',
            11 => 'السادس',
            12 => 'السابع',
            13 => 'الثامن',
            14 => 'التاسع',
            15 => 'العاشر',
            16 => 'الحادي عشر',
            17 => 'الثاني عشر',
            default => 'غير محدد',
        };
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'student_id');
    }
}
