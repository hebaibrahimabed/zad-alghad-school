@extends('layouts.zad')

@section('title', 'تعديل حالة التسجيل')
@section('page-title', 'تعديل حالة التسجيل')

@section('content')
<style>
    .form-card { background: white; border-radius: 16px; border: 1px solid #e8eaf6; box-shadow: 0 2px 12px rgba(26,35,126,0.05); padding: 24px; margin-bottom: 20px; }
    .field-row { display:grid; grid-template-columns: 1fr 1fr; gap:18px; margin-bottom:18px; }
    .field-wrap label { display:block; font-size:0.84rem; font-weight:600; color:#546e7a; margin-bottom:8px; }
    .field-wrap select, .field-wrap textarea { width:100%; border:2px solid #e8eaf6; border-radius:11px; padding:11px 14px; font-family:'Tajawal',sans-serif; font-size:0.9rem; color:#1a237e; background:#f8f9ff; }
    .btn-save { background: linear-gradient(135deg,#0d1257,#3949ab); color:white; border:none; border-radius:11px; padding:12px 28px; font-weight:700; cursor:pointer; }
    .btn-cancel { background:white; border:2px solid #e8eaf6; color:#546e7a; border-radius:11px; padding:12px 28px; text-decoration:none; font-weight:700; margin-right:10px; }
    .info-line { background:#f8f9ff; border-radius:10px; padding:12px 16px; margin-bottom:18px; font-size:0.87rem; color:#546e7a; }
    @media (max-width:768px){ .field-row{grid-template-columns:1fr;} }
</style>

<div class="info-line">
    <i class="fas fa-info-circle"></i>
    الشعبة: <b>{{ $registration->schoolClass->name ?? '—' }}</b> —
    السنة: <b>{{ $registration->schoolClass->academic_year ?? '—' }}</b>
</div>

<form action="{{ route('registrations.update', $registration->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-card">
        <div class="field-row">
            <div class="field-wrap">
                <label>حالة التسجيل <span style="color:#e53935;">*</span></label>
                <select name="current_status" required>
                    @foreach(['active'=>'نشط','withdrawn'=>'منسحب','graduated'=>'متخرج','transferred'=>'منقول'] as $val=>$label)
                        <option value="{{ $val }}" {{ old('current_status', $registration->current_status)==$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('current_status')<span style="color:#e53935; font-size:0.78rem;">{{ $message }}</span>@enderror
            </div>
            <div class="field-wrap">
                <label>حالة تسجيل الوزارة <span style="color:#e53935;">*</span></label>
                <select name="ministry_registration" required>
                    @foreach(['pending'=>'قيد الإجراء','registered'=>'مسجّل','exempt'=>'معفى'] as $val=>$label)
                        <option value="{{ $val }}" {{ old('ministry_registration', $registration->ministry_registration)==$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('ministry_registration')<span style="color:#e53935; font-size:0.78rem;">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="field-wrap">
            <label>ملاحظات</label>
            <textarea name="notes" rows="3">{{ old('notes', $registration->notes) }}</textarea>
        </div>
    </div>

    <button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ</button>
    <a href="{{ route('registrations.index', $registration->student_id) }}" class="btn-cancel">إلغاء</a>
</form>
@endsection
