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
</style>

<div style="margin-bottom:18px;">
    <a href="{{ route('registrations.index', $registration->student_id) }}" style="color:#3949ab; text-decoration:none; font-weight:700;">
        <i class="fas fa-arrow-right"></i> رجوع لتسجيلات الطالب
    </a>
</div>

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
            <thead><tr><th>الخصم</th><th>القيمة المطبّقة</th><th>السبب</th></tr></thead>
            <tbody>
                @foreach($registration->discounts as $sd)
                <tr>
                    <td>{{ $sd->discount->name ?? '—' }}</td>
                    <td>{{ $sd->applied_value }}</td>
                    <td>{{ $sd->reason }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="info-card">
    <h5><i class="fas fa-money-bill-wave"></i> الدفعات</h5>
    <a href="{{ route('payments.index', $registration->id) }}" style="color:#3949ab; font-weight:700; text-decoration:none;">
        عرض/إدارة كل الدفعات لهذا التسجيل <i class="fas fa-arrow-left"></i>
    </a>
</div>
@endsection
