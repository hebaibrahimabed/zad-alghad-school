<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route('student');

        return [
            'IDNumber' => 'required|string|digits:9|unique:students,IDNumber,' . $studentId,
            'studentName' => 'required|string|max:20',
            'FatherName' => 'required|string|max:20',
            'GrandfatherName' => 'nullable|string|max:20',
            'lastName' => 'required|string|max:20',
            'dateOfBirth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'lastCertificateObtained' => 'nullable|string|max:20',
            'healthCondition' => 'required|in:Healthy,disabled,injured',
            'parent_id' => 'nullable|exists:parents,id',
            'Parentmobile' => 'nullable|string|max:20',
            'RelativeGuardian' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'IDNumber.required' => 'رقم هوية الطالب مطلوب',
            'IDNumber.digits' => 'رقم هوية الطالب يجب أن يكون 9 أرقام',
            'IDNumber.unique' => 'رقم الهوية موجود مسبقاً لطالب آخر',
            'studentName.required' => 'اسم الطالب مطلوب',
            'FatherName.required' => 'اسم الأب مطلوب',
            'lastName.required' => 'اسم العائلة مطلوب',
            'dateOfBirth.required' => 'تاريخ الميلاد مطلوب',
            'dateOfBirth.before' => 'تاريخ الميلاد يجب أن يكون في الماضي',
            'gender.required' => 'الجنس مطلوب',
            'healthCondition.required' => 'الحالة الصحية مطلوبة',
            'parent_id.exists' => 'ولي الأمر المختار غير موجود',
        ];
    }
}
