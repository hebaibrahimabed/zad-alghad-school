@extends('layouts.zad')

@section('title', 'تفاصيل التسجيل')
@section('page-title', 'تفاصيل التسجيل')

@section('content')
<style>
    .info-card { background: white; border-radius: 16px; border: 1px solid #e8eaf6; box-shadow: 0 2px 12px rgba(26,35,126,0.05); padding: 24px; margin-bottom: 20px; }
    .info-card h5 { color:#1a237e; font-weight:700; margin-bottom:16px; }
    .info-grid { display:grid; grid-template-columns: repeat(3,1fr); gap:16px; }
    .info-item label { display:block; font-size:0.78rem; color:#90a4ae; margin-bottom:4px; }
    .info-item span { font-size:0.92rem; color:#1a237e; font-weight:600; }
    table.zad-table { width:100%; border-collapse:collapse; }
    table.zad-table th { background:#f8f9ff; color:#1a237e; font-size:0.8rem; padding:10px 14px; text-align:right; }
    table.zad-table td { padding:10px 14px; font-size:0.85rem; border-bottom:1px solid #f0f2fa; }
    .add-discount-form { display:flex; gap:10px; align-items:flex-end; margin-top:16px; padding-top:16px; border-top:1px solid #f0f2fa; flex-wrap:wrap; }
    .add-discount-form .field-wrap { flex:1; min-width:180px; }
    .add-discount-form label { display:block; font-size:0.78rem; color:#78909c; margin-bottom:6px; font-weight:600; }
    .add-discount-form select, .add-discount-form input { width:100%; border:2px solid #e8eaf6; border-radius:9px; padding:9px 12px; font-family:'Tajawal',sans-serif; font-size:0.85rem; }
    .btn-add-discount { background: linear-gradient(135deg,#0d1257,#3949ab); color:white; border:none; border-radius:9px; padding:10px 20px; font-weight:700; cursor:pointer; white-space:nowrap; }
    .btn-remove { border:none; background:none; cursor:pointer; color:#e53935; }
</style>

<div style="margin-bottom:18px;">
    <a href="{{ route('registrations.index', $registration->student_id) }}" style="color:#3949ab; text-decoration:none; font-weight:700;">
        <i class="fas fa-arrow-right"></i> رجوع لتسجيلات الطالب
    </a>
</div>

@if(session('success'))
<div style="background:#e8f5e9; color:#2e7d32; padding:14px 20px; border-radius:12px; margin-bottom:16px; font-weight:600;">{{ session('success') }}</div>
@endif
@if(session('error'))
<div style="background:#ffebee; color:#c62828; padding:14px 20px; border-radius:12px; margin-bottom:16px; font-weight:600;">{{ session('error') }}</div>
@endif

<div class="info-card">
    <h5><i class="fas fa-file-alt"></i> بيانات التسجيل</h5>
    <div class="info-grid">
        <div class="info-item"><label>الطالب</label><span>{{ $registration->student->full_name ?? '—' }}</span></div>
        <div class="info-item"><label>الشعبة</label><span>{{ $registration->schoolClass->name ?? '—' }}</span></div>
        <div class="info-item"><label>السنة الدراسية</label><span>{{ $registration->schoolClass->academic_year ?? '—' }}</span></div>
        <div class="info-item"><label>تاريخ التسجيل</label><span>{{ \Carbon\Carbon::parse($registration->registration_date)->format('Y-m-d') }}</span></div>
        <div class="info-item"><label>الحالة</label><span>{{ $registration->current_status }}</span></div>
        <div class="info-item"><label>تسجيل الوزارة</label><span>{{ $registration->ministry_registration }}</span></div>
    </div>
</div>

<div class="info-card">
    <h5><i class="fas fa-percent"></i> الخصومات المطبّقة</h5>
    @if($registration->discounts->isEmpty())
        <p style="color:#90a4ae;">لا يوجد خصومات مطبّقة على هذا التسجيل.</p>
    @else
        <table class="zad-table">
            <thead><tr><th>الخصم</th><th>القيمة المطبّقة</th><th>السبب</th><th>إجراءات</th></tr></thead>
            <tbody>
                @foreach($registration->discounts as $sd)
                <tr>
                    <td>{{ $sd->discount->name ?? '—' }}</td>
                    <td>{{ $sd->applied_value }}</td>
                    <td>{{ $sd->reason }}</td>
                    <td>
                        <form action="{{ route('registrations.discounts.remove', [$registration->id, $sd->id]) }}" method="POST"
                              onsubmit="return confirm('تأكيد إزالة هذا الخصم؟');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-remove" title="إزالة"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($availableDiscounts->isNotEmpty())
    <form action="{{ route('registrations.discounts.add', $registration->id) }}" method="POST" class="add-discount-form">
        @csrf
        <div class="field-wrap">
            <label>إضافة خصم يدوياً</label>
            <select name="discount_id" required>
                <option value="">-- اختر خصم --</option>
                @foreach($availableDiscounts as $d)
                    <option value="{{ $d->id }}">
                        {{ $d->name }} ({{ $d->type == 'general' ? 'عام' : 'خاص' }}) —
                        {{ $d->value_type == 'percentage' ? $d->value.'%' : number_format($d->value,2).' ₪' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="field-wrap">
            <label>السبب</label>
            <input type="text" name="reason" required placeholder="مثال: حالة اجتماعية خاصة">
        </div>
        <button type="submit" class="btn-add-discount"><i class="fas fa-plus"></i> إضافة</button>
    </form>
    @endif
</div>

<div class="info-card">
    <h5><i class="fas fa-money-bill-wave"></i> الدفعات</h5>
    <a href="{{ route('payments.index', $registration->id) }}" style="color:#3949ab; font-weight:700; text-decoration:none;">
        عرض/إدارة كل الدفعات لهذا التسجيل <i class="fas fa-arrow-left"></i>
    </a>
</div>
@endsection
