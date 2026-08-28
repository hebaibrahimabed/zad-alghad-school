@extends('layouts.zad')

@section('title', 'الدفعات المالية')
@section('page-title', 'الدفعات المالية')

@section('content')
<style>
    .summary-grid { display:grid; grid-template-columns: repeat(4,1fr); gap:16px; margin-bottom:20px; }
    .summary-box { background:white; border-radius:14px; border:1px solid #e8eaf6; padding:18px 20px; text-align:center; }
    .summary-box label { display:block; font-size:0.78rem; color:#90a4ae; margin-bottom:6px; }
    .summary-box .val { font-size:1.3rem; font-weight:800; }
    .val-fee { color:#1a237e; }
    .val-discount { color:#7b1fa2; }
    .val-paid { color:#2e7d32; }
    .val-outstanding { color:#c62828; }

    .list-card { background:white; border-radius:16px; border:1px solid #e8eaf6; box-shadow:0 2px 12px rgba(26,35,126,0.05); overflow:hidden; }
    .list-toolbar { padding:18px 24px; border-bottom:2px solid #f0f2fa; display:flex; justify-content:space-between; align-items:center; }
    .btn-add { background: linear-gradient(135deg,#0d1257,#3949ab); color:white; border:none; border-radius:10px; padding:10px 20px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:8px; }
    table.zad-table { width:100%; border-collapse:collapse; }
    table.zad-table th { background:#f8f9ff; color:#1a237e; font-size:0.8rem; padding:12px 16px; text-align:right; }
    table.zad-table td { padding:12px 16px; font-size:0.85rem; border-bottom:1px solid #f0f2fa; }
    .status-badge { padding:4px 12px; border-radius:16px; font-size:0.76rem; font-weight:700; }
    .status-pending { background:#fff8e1; color:#f9a825; }
    .status-partial { background:#fff3e0; color:#ef6c00; }
    .status-paid { background:#e8f5e9; color:#2e7d32; }
    .btn-icon { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; border:1.5px solid #e8eaf6; color:#546e7a; text-decoration:none; margin-left:5px; }
    .btn-icon:hover { background:#3949ab; color:white; border-color:#3949ab; }
    .empty-state { text-align:center; padding:50px 20px; color:#90a4ae; }
</style>

<div style="margin-bottom:18px;">
    <a href="{{ route('registrations.show', $registration->id) }}" style="color:#3949ab; text-decoration:none; font-weight:700;">
        <i class="fas fa-arrow-right"></i> رجوع لتفاصيل التسجيل — {{ $registration->student->full_name ?? '' }}
    </a>
</div>

@if(session('success'))
<div style="background:#e8f5e9; color:#2e7d32; padding:14px 20px; border-radius:12px; margin-bottom:16px; font-weight:600;">{{ session('success') }}</div>
@endif
@if(session('error'))
<div style="background:#ffebee; color:#c62828; padding:14px 20px; border-radius:12px; margin-bottom:16px; font-weight:600;">{{ session('error') }}</div>
@endif

<div class="summary-grid">
    <div class="summary-box"><label>رسوم الشعبة</label><div class="val val-fee">{{ number_format($registration->schoolClass->price ?? 0, 2) }} ₪</div></div>
    <div class="summary-box"><label>إجمالي الخصومات</label><div class="val val-discount">{{ number_format($totalDiscount, 2) }} ₪</div></div>
    <div class="summary-box"><label>إجمالي المدفوع</label><div class="val val-paid">{{ number_format($totalPaid, 2) }} ₪</div></div>
    <div class="summary-box"><label>المتبقي</label><div class="val val-outstanding">{{ number_format($totalOutstanding, 2) }} ₪</div></div>
</div>

<div class="list-card">
    <div class="list-toolbar">
        <b style="color:#1a237e;">سجل الدفعات</b>
        <a href="{{ route('payments.create', $registration->id) }}" class="btn-add"><i class="fas fa-plus"></i> تسجيل دفعة جديدة</a>
    </div>

    @if($registration->payments->isEmpty())
        <div class="empty-state">
            <i class="fas fa-receipt fa-3x" style="margin-bottom:14px;"></i>
            <p>لا يوجد دفعات مسجّلة بعد.</p>
        </div>
    @else
        <table class="zad-table">
            <thead>
                <tr><th>المستحق</th><th>المدفوع</th><th>المتبقي</th><th>تاريخ الاستحقاق</th><th>تاريخ الدفع</th><th>الحالة</th><th>إجراءات</th></tr>
            </thead>
            <tbody>
                @foreach($registration->payments as $payment)
                <tr>
                    <td>{{ number_format($payment->amount_due_month, 2) }} ₪</td>
                    <td>{{ number_format($payment->amount_paid, 2) }} ₪</td>
                    <td>{{ number_format($payment->total_outstanding, 2) }} ₪</td>
                    <td>{{ \Carbon\Carbon::parse($payment->due_date)->format('Y-m-d') }}</td>
                    <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d') : '—' }}</td>
                    <td>
                        @php $statusLabels = ['pending'=>'لم يُدفع','partial'=>'جزئي','paid'=>'مكتمل']; @endphp
                        <span class="status-badge status-{{ $payment->status }}">{{ $statusLabels[$payment->status] ?? $payment->status }}</span>
                    </td>
                    <td>
                        <a href="{{ route('payments.edit', $payment->id) }}" class="btn-icon" title="تعديل"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display:inline" onsubmit="return confirm('تأكيد حذف هذه الدفعة؟');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon" style="border:none; background:none; cursor:pointer;" title="حذف"><i class="fas fa-trash" style="color:#e53935;"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
