@extends('layouts.zad')

@section('title', 'إدارة الخصومات')
@section('page-title', 'إدارة الخصومات')

@section('content')
<style>
    .list-card { background: white; border-radius: 16px; border: 1px solid #e8eaf6; box-shadow: 0 2px 12px rgba(26,35,126,0.05); overflow: hidden; }
    .list-toolbar { padding: 18px 24px; border-bottom: 2px solid #f0f2fa; display: flex; justify-content:space-between; align-items:center; gap:12px; }
    .filter-tabs a { padding:8px 16px; border-radius:9px; text-decoration:none; font-size:0.85rem; font-weight:700; color:#78909c; margin-left:6px; }
    .filter-tabs a.active { background:#e3f2fd; color:#1565c0; }
    .btn-add { background: linear-gradient(135deg,#0d1257,#3949ab); color:white; border:none; border-radius:10px; padding:10px 20px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:8px; }
    table.zad-table { width:100%; border-collapse:collapse; }
    table.zad-table th { background:#f8f9ff; color:#1a237e; font-size:0.8rem; padding:12px 16px; text-align:right; }
    table.zad-table td { padding:12px 16px; font-size:0.85rem; border-bottom:1px solid #f0f2fa; }
    .type-badge { padding:4px 12px; border-radius:16px; font-size:0.76rem; font-weight:700; }
    .type-general { background:#e8f5e9; color:#2e7d32; }
    .type-special { background:#fff3e0; color:#ef6c00; }
    .active-toggle { padding:4px 12px; border-radius:16px; font-size:0.76rem; font-weight:700; border:none; cursor:pointer; }
    .active-on { background:#e8f5e9; color:#2e7d32; }
    .active-off { background:#f5f5f5; color:#9e9e9e; }
    .btn-icon { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; border:1.5px solid #e8eaf6; color:#546e7a; text-decoration:none; margin-left:5px; }
    .btn-icon:hover { background:#3949ab; color:white; border-color:#3949ab; }
    .empty-state { text-align:center; padding:50px 20px; color:#90a4ae; }
</style>

@if(session('success'))
<div style="background:#e8f5e9; color:#2e7d32; padding:14px 20px; border-radius:12px; margin-bottom:16px; font-weight:600;">{{ session('success') }}</div>
@endif
@if(session('error'))
<div style="background:#ffebee; color:#c62828; padding:14px 20px; border-radius:12px; margin-bottom:16px; font-weight:600;">{{ session('error') }}</div>
@endif

<div class="list-card">
    <div class="list-toolbar">
        <div class="filter-tabs">
            <a href="{{ route('discounts.index') }}" class="{{ !request('type') ? 'active' : '' }}">الكل</a>
            <a href="{{ route('discounts.index', ['type'=>'general']) }}" class="{{ request('type')=='general' ? 'active' : '' }}">عامة</a>
            <a href="{{ route('discounts.index', ['type'=>'special']) }}" class="{{ request('type')=='special' ? 'active' : '' }}">خاصة</a>
        </div>
        <a href="{{ route('discounts.create') }}" class="btn-add"><i class="fas fa-plus"></i> إضافة خصم جديد</a>
    </div>

    @if($discounts->count() === 0)
        <div class="empty-state">
            <i class="fas fa-percent fa-3x" style="margin-bottom:14px;"></i>
            <p>لا يوجد خصومات مضافة بعد.</p>
        </div>
    @else
        <table class="zad-table">
            <thead>
                <tr><th>اسم الخصم</th><th>النوع</th><th>القيمة</th><th>الفترة</th><th>عدد مرات التطبيق</th><th>الحالة</th><th>إجراءات</th></tr>
            </thead>
            <tbody>
                @foreach($discounts as $d)
                <tr>
                    <td><b>{{ $d->name }}</b>{!! $d->notes ? '<br><small style="color:#90a4ae;">'.$d->notes.'</small>' : '' !!}</td>
                    <td><span class="type-badge type-{{ $d->type }}">{{ $d->type == 'general' ? 'عام' : 'خاص' }}</span></td>
                    <td>{{ $d->value_type == 'percentage' ? $d->value.'%' : number_format($d->value,2).' ₪' }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($d->start_date)->format('Y-m-d') }}
                        @if($d->end_date) — {{ \Carbon\Carbon::parse($d->end_date)->format('Y-m-d') }} @else (مفتوح) @endif
                    </td>
                    <td>{{ $d->student_discounts_count }}</td>
                    <td>
                        <form action="{{ route('discounts.toggle-active', $d->id) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="active-toggle {{ $d->is_active ? 'active-on' : 'active-off' }}">
                                {{ $d->is_active ? 'مُفعّل' : 'مُعطّل' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('discounts.edit', $d->id) }}" class="btn-icon" title="تعديل"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('discounts.destroy', $d->id) }}" method="POST" style="display:inline" onsubmit="return confirm('تأكيد حذف هذا الخصم؟');">
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
