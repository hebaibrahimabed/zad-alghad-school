<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $parentId = $this->route('parent');

        return [
            'national_id' => 'required|string|max:20|unique:parents,national_id,' . $parentId,
            'first_name' => 'required|string|max:20',
            'second_name' => 'required|string|max:20',
            'third_name' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date|before:today',
            'phone' => 'required|string|regex:/^[0-9+]{10,15}$/',
            'relation' => 'required|in:father,mother,brother,sister,uncle,aunt,grandfather,grandmother,other',
            'address' => 'nullable|string|max:255',
            'housing_status' => 'nullable|in:owned,rented,tent,displaced',
            'work' => 'nullable|string|max:50',
            'orphan_status_student' => 'required|in:not_orphan,father,mother,both',
        ];
    }

    public function messages(): array
    {
        return [
            'national_id.required' => 'رقم الهوية مطلوب',
            'national_id.unique' => 'رقم الهوية موجود مسبقاً لولي أمر آخر',
            'first_name.required' => 'الاسم الأول مطلوب',
            'second_name.required' => 'اسم الأب مطلوب',
            'third_name.required' => 'اسم العائلة مطلوب',
            'gender.required' => 'الجنس مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.regex' => 'صيغة رقم الهاتف غير صحيحة',
            'relation.required' => 'صلة القرابة مطلوبة',
            'orphan_status_student.required' => 'حالة اليتم مطلوبة',
        ];
    }
}
