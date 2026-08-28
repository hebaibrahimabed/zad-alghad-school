@extends('layouts.zad')

@section('title', 'ولي الأمر: ' . $parent->first_name)
@section('page-title', 'تفاصيل ولي الأمر')

@section('content')
<style>
    .info-card { background: white; border-radius: 16px; border: 1px solid #e8eaf6; box-shadow: 0 2px 12px rgba(26,35,126,0.05); padding: 24px; margin-bottom: 20px; }
    .info-card h5 { color: #1a237e; font-weight: 700; margin-bottom: 16px; display:flex; align-items:center; gap:8px; }
    .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .info-item label { display:block; font-size:0.78rem; color:#90a4ae; font-weight:600; margin-bottom:4px; }
    .info-item span { font-size: 0.92rem; color: #1a237e; font-weight: 600; }
    .child-card { border: 2px solid #e8eaf6; border-radius: 12px; padding: 16px 20px; margin-bottom: 12px; display:flex; justify-content:space-between; align-items:center; }
    .child-name { font-weight: 700; color: #1a237e; }
    .child-meta { font-size: 0.82rem; color: #78909c; margin-top: 4px; }
    .btn-view-student { background: white; border: 2px solid #3949ab; color: #3949ab; border-radius: 10px; padding: 8px 18px; font-weight:700; text-decoration:none; }
    .btn-view-student:hover { background:#3949ab; color:white; }
    .action-bar { display:flex; gap:10px; margin-bottom:20px; }
    .btn-edit-parent { background: linear-gradient(135deg,#0d1257,#3949ab); color:white; padding:10px 22px; border-radius:10px; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:8px; }
    .btn-back { background:white; border:2px solid #e8eaf6; color:#546e7a; padding:10px 22px; border-radius:10px; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:8px; }
</style>

<div class="action-bar">
    <a href="{{ route('parents.edit', $parent->id) }}" class="btn-edit-parent"><i class="fas fa-edit"></i> تعديل بيانات ولي الأمر</a>
    <a href="{{ route('parents.index') }}" class="btn-back"><i class="fas fa-arrow-right"></i> رجوع للقائمة</a>
</div>

@php
    $relationLabels = ['father'=>'أب','mother'=>'أم','brother'=>'أخ','sister'=>'أخت','uncle'=>'عم/خال','aunt'=>'عمة/خالة','grandfather'=>'جد','grandmother'=>'جدة','other'=>'أخرى'];
    $housingLabels = ['owned'=>'ملك','rented'=>'إيجار','tent'=>'خيمة','displaced'=>'نازح'];
    $orphanLabels = ['not_orphan'=>'غير يتيم','father'=>'يتيم الأب','mother'=>'يتيم الأم','both'=>'يتيم الأبوين'];
@endphp

<div class="info-card">
    <h5><i class="fas fa-user-shield"></i> بيانات ولي الأمر</h5>
    <div class="info-grid">
        <div class="info-item"><label>الاسم الكامل</label><span>{{ $parent->first_name }} {{ $parent->second_name }} {{ $parent->third_name }}</span></div>
        <div class="info-item"><label>رقم الهوية</label><span>{{ $parent->national_id }}</span></div>
        <div class="info-item"><label>رقم الهاتف</label><span>{{ $parent->phone }}</span></div>
        <div class="info-item"><label>صلة القرابة</label><span>{{ $relationLabels[$parent->relation] ?? $parent->relation }}</span></div>
        <div class="info-item"><label>الحالة السكنية</label><span>{{ $housingLabels[$parent->housing_status] ?? '—' }}</span></div>
        <div class="info-item"><label>العمل</label><span>{{ $parent->work ?? '—' }}</span></div>
        <div class="info-item"><label>العنوان</label><span>{{ $parent->address ?? '—' }}</span></div>
        <div class="info-item"><label>حالة يتم الأبناء</label><span>{{ $orphanLabels[$parent->orphan_status_student] ?? '—' }}</span></div>
    </div>
</div>

<div class="info-card">
    <h5><i class="fas fa-child"></i> الأبناء المسجّلون ({{ $parent->students->count() }})</h5>

    @forelse($parent->students as $student)
        <div class="child-card">
            <div>
                <div class="child-name">{{ $student->full_name }}</div>
                <div class="child-meta">
                    رقم الهوية: {{ $student->IDNumber }}
                    @if($student->registrations->isNotEmpty())
                        — {{ $student->registrations->last()->schoolClass->name ?? '' }}
                        ({{ $student->registrations->last()->schoolClass->academic_year ?? '' }})
                    @else
                        — <span style="color:#e53935;">بدون تسجيل بشعبة</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('students.show', $student->id) }}" class="btn-view-student">عرض الطالب</a>
        </div>
    @empty
        <p style="color:#90a4ae;">لا يوجد أبناء مرتبطين بولي الأمر هذا حالياً.</p>
    @endforelse
</div>
@endsection
