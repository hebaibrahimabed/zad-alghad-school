@extends('layouts.zad')

@section('title', 'تعديل بيانات ولي الأمر')
@section('page-title', 'تعديل بيانات ولي الأمر')

@section('content')
<style>
    .form-card { background: white; border-radius: 16px; border: 1px solid #e8eaf6; box-shadow: 0 2px 12px rgba(26,35,126,0.05); overflow: hidden; margin-bottom: 20px; }
    .form-card-header { padding: 18px 24px; border-bottom: 2px solid #f0f2fa; display:flex; align-items:center; gap:10px; }
    .form-card-header h5 { margin:0; color:#1a237e; font-weight:700; }
    .form-card-body { padding: 24px; }
    .field-row { display:grid; gap:18px; margin-bottom:18px; }
    .cols-2 { grid-template-columns: 1fr 1fr; }
    .cols-3 { grid-template-columns: 1fr 1fr 1fr; }
    .field-wrap label { display:flex; align-items:center; gap:6px; font-size:0.84rem; font-weight:600; color:#546e7a; margin-bottom:8px; }
    .field-wrap label .req { color:#e53935; }
    .field-wrap input, .field-wrap select { width:100%; border:2px solid #e8eaf6; border-radius:11px; padding:11px 14px; font-family:'Tajawal',sans-serif; font-size:0.9rem; color:#1a237e; background:#f8f9ff; }
    .field-wrap input:focus, .field-wrap select:focus { border-color:#3949ab; background:white; outline:none; }
    .invalid-feedback { display:block; color:#e53935; font-size:0.78rem; margin-top:5px; }
    .action-bar { background:white; border-radius:16px; border:1px solid #e8eaf6; padding:20px 24px; display:flex; gap:12px; }
    .btn-save { background: linear-gradient(135deg,#0d1257,#3949ab); color:white; border:none; border-radius:11px; padding:12px 28px; font-weight:700; cursor:pointer; }
    .btn-cancel { background:white; border:2px solid #e8eaf6; color:#546e7a; border-radius:11px; padding:12px 28px; text-decoration:none; font-weight:700; }
    @media (max-width:768px){ .cols-2,.cols-3{grid-template-columns:1fr;} }
</style>

@if($errors->any())
<div style="background:#ffebee; border-radius:12px; padding:16px 20px; margin-bottom:20px; border-right:4px solid #e53935;">
    <ul style="margin:0; padding-right:18px; color:#c62828;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('parents.update', $parent->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-card">
        <div class="form-card-header"><i class="fas fa-user-shield"></i><h5>بيانات ولي الأمر</h5></div>
        <div class="form-card-body">
            <div class="field-row cols-2">
                <div class="field-wrap">
                    <label>رقم الهوية <span class="req">*</span></label>
                    <input type="text" name="national_id" value="{{ old('national_id', $parent->national_id) }}" required>
                    @error('national_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="field-wrap">
                    <label>رقم الهاتف <span class="req">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $parent->phone) }}" required>
                    @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="field-row cols-3">
                <div class="field-wrap">
                    <label>الاسم الأول <span class="req">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $parent->first_name) }}" required>
                    @error('first_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="field-wrap">
                    <label>اسم الأب <span class="req">*</span></label>
                    <input type="text" name="second_name" value="{{ old('second_name', $parent->second_name) }}" required>
                    @error('second_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="field-wrap">
                    <label>اسم العائلة <span class="req">*</span></label>
                    <input type="text" name="third_name" value="{{ old('third_name', $parent->third_name) }}" required>
                    @error('third_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="field-row cols-3">
                <div class="field-wrap">
                    <label>الجنس <span class="req">*</span></label>
                    <select name="gender" required>
                        <option value="male" {{ old('gender', $parent->gender)=='male'?'selected':'' }}>ذكر</option>
                        <option value="female" {{ old('gender', $parent->gender)=='female'?'selected':'' }}>أنثى</option>
                    </select>
                </div>
                <div class="field-wrap">
                    <label>تاريخ الميلاد</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', optional($parent->birth_date)->format('Y-m-d')) }}">
                </div>
                <div class="field-wrap">
                    <label>صلة القرابة <span class="req">*</span></label>
                    <select name="relation" required>
                        @php $rel = ['father'=>'أب','mother'=>'أم','brother'=>'أخ','sister'=>'أخت','uncle'=>'عم/خال','aunt'=>'عمة/خالة','grandfather'=>'جد','grandmother'=>'جدة','other'=>'أخرى']; @endphp
                        @foreach($rel as $val => $label)
                            <option value="{{ $val }}" {{ old('relation', $parent->relation)==$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="field-row cols-3">
                <div class="field-wrap">
                    <label>الحالة السكنية</label>
                    <select name="housing_status">
                        <option value="">-- اختر --</option>
                        @foreach(['owned'=>'ملك','rented'=>'إيجار','tent'=>'خيمة','displaced'=>'نازح'] as $val=>$label)
                            <option value="{{ $val }}" {{ old('housing_status', $parent->housing_status)==$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-wrap">
                    <label>العمل</label>
                    <input type="text" name="work" value="{{ old('work', $parent->work) }}">
                </div>
                <div class="field-wrap">
                    <label>حالة يتم الأبناء <span class="req">*</span></label>
                    <select name="orphan_status_student" required>
                        @foreach(['not_orphan'=>'غير يتيم','father'=>'يتيم الأب','mother'=>'يتيم الأم','both'=>'يتيم الأبوين'] as $val=>$label)
                            <option value="{{ $val }}" {{ old('orphan_status_student', $parent->orphan_status_student)==$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="field-row cols-2">
                <div class="field-wrap" style="grid-column: 1 / -1;">
                    <label>العنوان</label>
                    <input type="text" name="address" value="{{ old('address', $parent->address) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="action-bar">
        <button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ التعديلات</button>
        <a href="{{ route('parents.show', $parent->id) }}" class="btn-cancel"><i class="fas fa-times"></i> إلغاء</a>
    </div>
</form>
@endsection
