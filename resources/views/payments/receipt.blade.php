<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إيصال دفعة - {{ $payment->registration->student->full_name ?? '' }}</title>
    <style>
        * { box-sizing: border-box; margin:0; padding:0; }
        body { font-family: 'Tahoma', 'Arial', sans-serif; background:#f0f2fa; padding:30px; color:#263238; }
        .receipt { max-width: 620px; margin: 0 auto; background:white; border-radius:14px; padding:36px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .receipt-header { text-align:center; border-bottom:3px solid #1a237e; padding-bottom:18px; margin-bottom:22px; }
        .receipt-header h1 { color:#1a237e; font-size:1.4rem; margin-bottom:6px; }
        .receipt-header p { color:#78909c; font-size:0.85rem; }
        .receipt-meta { display:flex; justify-content:space-between; font-size:0.82rem; color:#78909c; margin-bottom:24px; }
        .info-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px dashed #e0e0e0; font-size:0.92rem; }
        .info-row label { color:#78909c; }
        .info-row span { font-weight:700; color:#1a237e; }
        .amount-box { background:#e8f5e9; border-radius:12px; padding:18px 22px; margin:22px 0; text-align:center; }
        .amount-box .amount { font-size:2rem; font-weight:800; color:#2e7d32; }
        .amount-box .label { font-size:0.82rem; color:#546e7a; margin-top:4px; }
        .status-tag { display:inline-block; padding:5px 16px; border-radius:20px; font-size:0.8rem; font-weight:700; }
        .status-paid { background:#e8f5e9; color:#2e7d32; }
        .status-partial { background:#fff3e0; color:#ef6c00; }
        .status-pending { background:#fff8e1; color:#f9a825; }
        .footer-note { text-align:center; color:#90a4ae; font-size:0.78rem; margin-top:28px; }
        .print-btn { display:block; margin: 24px auto 0; background: linear-gradient(135deg,#0d1257,#3949ab); color:white; border:none; border-radius:10px; padding:12px 30px; font-weight:700; cursor:pointer; font-size:0.9rem; }
        @media print {
            body { background:white; padding:0; }
            .receipt { box-shadow:none; max-width:100%; }
            .print-btn { display:none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>مدرسة زاد الغد</h1>
            <p>إيصال استلام دفعة مالية</p>
        </div>

        <div class="receipt-meta">
            <span>رقم الإيصال: #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
            <span>{{ now()->format('Y-m-d') }}</span>
        </div>

        <div class="info-row"><label>اسم الطالب</label><span>{{ $payment->registration->student->full_name ?? '—' }}</span></div>
        <div class="info-row"><label>رقم هوية الطالب</label><span>{{ $payment->registration->student->IDNumber ?? '—' }}</span></div>
        <div class="info-row"><label>ولي الأمر</label>
            <span>
                @if($payment->registration->student->parent ?? null)
                    {{ $payment->registration->student->parent->first_name }} {{ $payment->registration->student->parent->second_name }}
                @else — @endif
            </span>
        </div>
        <div class="info-row"><label>الشعبة الدراسية</label><span>{{ $payment->registration->schoolClass->name ?? '—' }}</span></div>
        <div class="info-row"><label>تاريخ الاستحقاق</label><span>{{ \Carbon\Carbon::parse($payment->due_date)->format('Y-m-d') }}</span></div>
        <div class="info-row"><label>تاريخ الدفع</label><span>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d') : 'لم يُدفع بعد' }}</span></div>
        <div class="info-row"><label>طريقة الدفع</label><span>{{ $payment->payment_method == 'cash' ? 'نقدي' : ($payment->payment_method == 'app' ? 'تطبيق إلكتروني' : '—') }}</span></div>
        <div class="info-row">
            <label>حالة الدفعة</label>
            @php $statusLabels = ['pending'=>'لم يُدفع','partial'=>'جزئي','paid'=>'مكتمل']; @endphp
            <span class="status-tag status-{{ $payment->status }}">{{ $statusLabels[$payment->status] ?? $payment->status }}</span>
        </div>

        <div class="amount-box">
            <div class="amount">{{ number_format($payment->amount_paid, 2) }} ₪</div>
            <div class="label">المبلغ المدفوع (من أصل {{ number_format($payment->amount_due_month, 2) }} ₪ مستحق)</div>
        </div>

        @if($payment->total_outstanding > 0)
        <div class="info-row"><label>المتبقي على هذه الدفعة</label><span style="color:#c62828;">{{ number_format($payment->total_outstanding, 2) }} ₪</span></div>
        @endif

        @if($payment->notes)
        <div class="info-row"><label>ملاحظات</label><span>{{ $payment->notes }}</span></div>
        @endif

        <p class="footer-note">هذا الإيصال صادر إلكترونياً من نظام إدارة مدرسة زاد الغد</p>

        <button class="print-btn" onclick="window.print()">🖨 طباعة الإيصال</button>
    </div>
</body>
</html>
