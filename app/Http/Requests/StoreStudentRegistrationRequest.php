<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // ===== 1) بيانات الطالب =====
            'student.IDNumber' => 'required|string|digits:9|unique:students,IDNumber',
            'student.studentName' => 'required|string|max:20',
            'student.FatherName' => 'required|string|max:20',
            'student.GrandfatherName' => 'nullable|string|max:20',
            'student.lastName' => 'required|string|max:20',
            'student.dateOfBirth' => 'required|date|before:today',
            'student.gender' => 'required|in:male,female',
            'student.lastCertificateObtained' => 'nullable|string|max:20',
            'student.healthCondition' => 'required|in:Healthy,disabled,injured',

            // ===== 2) بيانات ولي الأمر =====
            'parent.national_id' => 'required|string|max:20',
            'parent.first_name' => 'required|string|max:20',
            'parent.second_name' => 'required|string|max:20',
            'parent.third_name' => 'required|string|max:20',
            'parent.gender' => 'required|in:male,female',
            'parent.birth_date' => 'nullable|date|before:today',
            'parent.phone' => 'required|string|regex:/^[0-9+]{10,15}$/',
            'parent.relation' => 'required|in:father,mother,brother,sister,uncle,aunt,grandfather,grandmother,other',
            'parent.address' => 'nullable|string|max:255',
            'parent.housing_status' => 'nullable|in:owned,rented,tent,displaced',
            'parent.work' => 'nullable|string|max:50',
            'parent.orphan_status_student' => 'required|in:not_orphan,father,mother,both',

            // ===== 3) بيانات التسجيل =====
            'registration.class_id' => 'required|exists:classes,id',
            'registration.registration_date' => 'required|date|before_or_equal:today',
            'registration.ministry_registration' => 'required|in:pending,registered,exempt',
            'registration.notes' => 'nullable|string',

            // ===== 4) الخصومات الخاصة (اختيارية) =====
            'discounts.special' => 'nullable|array',
            'discounts.special.*.discount_id' => 'required_with:discounts.special|exists:discounts,id',
            'discounts.special.*.reason' => 'required_with:discounts.special|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'student.IDNumber.required' => 'رقم هوية الطالب مطلوب',
            'student.IDNumber.digits' => 'رقم هوية الطالب يجب أن يكون 9 أرقام',
            'student.IDNumber.unique' => 'رقم الهوية موجود مسبقاً لطالب آخر',
            'student.studentName.required' => 'اسم الطالب مطلوب',
            'student.FatherName.required' => 'اسم الأب مطلوب',
            'student.lastName.required' => 'اسم العائلة مطلوب',
            'student.dateOfBirth.required' => 'تاريخ ميلاد الطالب مطلوب',
            'student.dateOfBirth.before' => 'تاريخ الميلاد يجب أن يكون في الماضي',
            'student.gender.required' => 'جنس الطالب مطلوب',
            'student.healthCondition.required' => 'الحالة الصحية مطلوبة',

            'parent.national_id.required' => 'رقم هوية ولي الأمر مطلوب',
            'parent.first_name.required' => 'الاسم الأول لولي الأمر مطلوب',
            'parent.second_name.required' => 'اسم الأب لولي الأمر مطلوب',
            'parent.third_name.required' => 'اسم العائلة لولي الأمر مطلوب',
            'parent.gender.required' => 'جنس ولي الأمر مطلوب',
            'parent.phone.required' => 'رقم هاتف ولي الأمر مطلوب',
            'parent.phone.regex' => 'صيغة رقم الهاتف غير صحيحة',
            'parent.relation.required' => 'صلة القرابة مطلوبة',
            'parent.orphan_status_student.required' => 'حالة اليتم مطلوبة',

            'registration.class_id.required' => 'الشعبة الدراسية مطلوبة',
            'registration.class_id.exists' => 'الشعبة المختارة غير موجودة',
            'registration.registration_date.required' => 'تاريخ التسجيل مطلوب',
            'registration.ministry_registration.required' => 'حالة التسجيل بالوزارة مطلوبة',

            'discounts.special.*.discount_id.exists' => 'أحد الخصومات المختارة غير موجود',
            'discounts.special.*.reason.required_with' => 'سبب الخصم الخاص مطلوب',
        ];
    }

    public function attributes(): array
    {
        return [
            'student.IDNumber' => 'رقم هوية الطالب',
            'parent.national_id' => 'رقم هوية ولي الأمر',
            'registration.class_id' => 'الشعبة',
        ];
    }
}
