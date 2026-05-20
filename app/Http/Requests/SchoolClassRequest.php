<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'level_id'      => 'required|exists:levels,id',
            'name'          => 'required|string|max:100',
            'academic_year' => 'required|string|max:20',
            'price'         => 'required|numeric|min:0',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'min_capacity'  => 'nullable|integer|min:1|max:255',
            'max_capacity'  => 'nullable|integer|min:1|max:255|gte:min_capacity',
        ];
    }

    public function messages(): array
    {
        return [
            'level_id.required'      => 'يجب اختيار الصف الدراسي',
            'level_id.exists'        => 'الصف الدراسي غير موجود',
            'name.required'          => 'اسم الشعبة مطلوب',
            'name.max'               => 'اسم الشعبة لا يتجاوز 100 حرف',
            'academic_year.required' => 'السنة الدراسية مطلوبة',
            'price.required'         => 'السعر مطلوب',
            'price.numeric'          => 'السعر يجب أن يكون رقماً',
            'price.min'              => 'السعر لا يمكن أن يكون سالباً',
            'start_date.required'    => 'تاريخ البداية مطلوب',
            'end_date.required'      => 'تاريخ النهاية مطلوب',
            'end_date.after'         => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            'max_capacity.gte'       => 'الحد الأقصى يجب أن يكون أكبر من أو يساوي الحد الأدنى',
        ];
    }
}
