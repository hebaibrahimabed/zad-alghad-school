@extends('layouts.zad')

@section('title', 'تعديل الخصم')
@section('page-title', 'تعديل الخصم')

@section('content')
<style>
    .form-card { background: white; border-radius: 16px; border: 1px solid #e8eaf6; box-shadow: 0 2px 12px rgba(26,35,126,0.05); padding: 24px; margin-bottom: 20px; }
    .field-row { display:grid; grid-template-columns: 1fr 1fr; gap:18px; margin-bottom:18px; }
    .field-wrap label { display:block; font-size:0.84rem; font-weight:600; color:#546e7a; margin-bottom:8px; }
    .field-wrap input, .field-wrap select, .field-wrap textarea { width:100%; border:2px solid #e8eaf6; border-radius:11px; padding:11px 14px; font-family:'Tajawal',sans-serif; font-size:0.9rem; color:#1a237e; background:#f8f9ff; }
    .btn-save { background: linear-gradient(135deg,#0d1257,#3949ab); color:white; border:none; border-radius:11px; padding:12px 28px; font-weight:700; cursor:pointer; }
    .btn-cancel { background:white; border:2px solid #e8eaf6; color:#546e7a; border-radius:11px; padding:12px 28px; text-decoration:none; font-weight:700; margin-right:10px; }
    .checkbox-wrap { display:flex; align-items:center; gap:10px; }
    .checkbox-wrap input { width:auto; }
    .hint { font-size:0.76rem; color:#9e9e9e; margin-top:6px; }
    @media (max-width:768px){ .field-row{grid-template-columns:1fr;} }
</style>

@if($errors->any())
<div style="background:#ffebee; border-radius:12px; padding:16px 20px; margin-bottom:20px; border-right:4px solid #e53935;">
    <ul style="margin:0; padding-right:18px; color:#c62828;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('discounts.update', $discount->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-card">
        <div class="field-row">
            <div class="field-wrap">
                <label>اسم الخصم <span style="color:#e53935;">*</span></label>
                <input type="text" name="name" value="{{ old('name', $discount->name) }}" required>
            </div>
            <div class="field-wrap">
                <label>نوع الخصم <span style="color:#e53935;">*</span></label>
                <select name="type" required>
                    <option value="general" {{ old('type', $discount->type)=='general'?'selected':'' }}>عام (يُطبّق تلقائياً حسب شرط، مثل الإخوة)</option>
                    <option value="special" {{ old('type', $discount->type)=='special'?'selected':'' }}>خاص (يُطبّق يدوياً من موظف التسجيل)</option>
                </select>
            </div>
        </div>
        <div class="field-row">
            <div class="field-wrap">
                <label>طريقة احتساب القيمة <span style="color:#e53935;">*</span></label>
                <select name="value_type" required>
                    <option value="percentage" {{ old('value_type', $discount->value_type)=='percentage'?'selected':'' }}>نسبة مئوية %</option>
                    <option value="fixed" {{ old('value_type', $discount->value_type)=='fixed'?'selected':'' }}>مبلغ ثابت ₪</option>
                </select>
            </div>
            <div class="field-wrap">
                <label>القيمة <span style="color:#e53935;">*</span></label>
                <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $discount->value) }}" required>
            </div>
        </div>
        <div class="field-row">
            <div class="field-wrap">
                <label>تاريخ بداية السريان <span style="color:#e53935;">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d')) }}" required>
            </div>
            <div class="field-wrap">
                <label>تاريخ الانتهاء (اختياري)</label>
                <input type="date" name="end_date" value="{{ old('end_date', optional($discount->end_date)->format('Y-m-d')) }}">
            </div>
        </div>
        <div class="field-wrap" style="margin-bottom:18px;">
            <label class="checkbox-wrap">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
                مفعّل (يظهر ويُطبّق فوراً)
            </label>
        </div>
        <div class="field-wrap">
            <label>ملاحظات</label>
            <textarea name="notes" rows="2">{{ old('notes', $discount->notes) }}</textarea>
        </div>
    </div>

    <button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ التعديلات</button>
    <a href="{{ route('discounts.index') }}" class="btn-cancel">إلغاء</a>
</form>
@endsection
