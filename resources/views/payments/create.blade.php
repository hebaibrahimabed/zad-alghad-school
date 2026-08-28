@extends('layouts.zad')

@section('title', 'تسجيل دفعة جديدة')
@section('page-title', 'تسجيل دفعة جديدة')

@section('content')
<style>
    .form-card { background: white; border-radius: 16px; border: 1px solid #e8eaf6; box-shadow: 0 2px 12px rgba(26,35,126,0.05); padding: 24px; margin-bottom: 20px; }
    .field-row { display:grid; grid-template-columns: 1fr 1fr; gap:18px; margin-bottom:18px; }
    .field-wrap label { display:block; font-size:0.84rem; font-weight:600; color:#546e7a; margin-bottom:8px; }
    .field-wrap input, .field-wrap select, .field-wrap textarea { width:100%; border:2px solid #e8eaf6; border-radius:11px; padding:11px 14px; font-family:'Tajawal',sans-serif; font-size:0.9rem; color:#1a237e; background:#f8f9ff; }
    .btn-save { background: linear-gradient(135deg,#0d1257,#3949ab); color:white; border:none; border-radius:11px; padding:12px 28px; font-weight:700; cursor:pointer; }
    .btn-cancel { background:white; border:2px solid #e8eaf6; color:#546e7a; border-radius:11px; padding:12px 28px; text-decoration:none; font-weight:700; margin-right:10px; }
    .info-line { background:#f8f9ff; border-radius:10px; padding:12px 16px; margin-bottom:18px; font-size:0.87rem; color:#546e7a; }
    @media (max-width:768px){ .field-row{grid-template-columns:1fr;} }
</style>

<div class="info-line">
    <i class="fas fa-info-circle"></i>
    الطالب: <b>{{ $registration->student->full_name ?? '' }}</b> —
    الشعبة: <b>{{ $registration->schoolClass->name ?? '' }}</b> —
    الرسوم الكاملة: <b>{{ number_format($registration->schoolClass->price ?? 0, 2) }} ₪</b>
</div>

@if($errors->any())
<div style="background:#ffebee; border-radius:12px; padding:16px 20px; margin-bottom:20px; border-right:4px solid #e53935;">
    <ul style="margin:0; padding-right:18px; color:#c62828;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('payments.store', $registration->id) }}" method="POST">
    @csrf
    <div class="form-card">
        <div class="field-row">
            <div class="field-wrap">
                <label>المبلغ المستحق <span style="color:#e53935;">*</span></label>
                <input type="number" step="0.01" min="0" name="amount_due_month" value="{{ old('amount_due_month') }}" required>
            </div>
            <div class="field-wrap">
                <label>المبلغ المدفوع الآن <span style="color:#e53935;">*</span></label>
                <input type="number" step="0.01" min="0" name="amount_paid" value="{{ old('amount_paid', 0) }}" required>
            </div>
        </div>
        <div class="field-row">
            <div class="field-wrap">
                <label>تاريخ الاستحقاق <span style="color:#e53935;">*</span></label>
                <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d')) }}" required>
            </div>
            <div class="field-wrap">
                <label>تاريخ الدفع (إن وُجد)</label>
                <input type="date" name="paid_at" value="{{ old('paid_at') }}">
            </div>
        </div>
        <div class="field-row">
            <div class="field-wrap">
                <label>طريقة الدفع</label>
                <select name="payment_method">
                    <option value="">-- غير محدد --</option>
                    <option value="cash" {{ old('payment_method')=='cash'?'selected':'' }}>نقدي</option>
                    <option value="app" {{ old('payment_method')=='app'?'selected':'' }}>تطبيق إلكتروني</option>
                </select>
            </div>
        </div>
        <div class="field-wrap">
            <label>ملاحظات</label>
            <textarea name="notes" rows="2">{{ old('notes') }}</textarea>
        </div>
    </div>

    <button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ الدفعة</button>
    <a href="{{ route('payments.index', $registration->id) }}" class="btn-cancel">إلغاء</a>
</form>
@endsection
