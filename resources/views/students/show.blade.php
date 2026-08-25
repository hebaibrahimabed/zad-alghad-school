{{-- resources/views/students/show.blade.php --}}
@extends('layouts.zad')
@section('title', 'بيانات الطالب - ' . $student->full_name)
@section('page-title', 'بيانات الطالب')

@section('content')
<style>
    .profile-header-card {
        background: linear-gradient(135deg, #0d1257 0%, #283593 60%, #3949ab 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 24px;
        position: relative;
        overflow: hidden;
    }
    .profile-header-card::before {
        content: '';
        position: absolute;
        right: -50px; top: -50px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }
    .profile-header-card::after {
        content: '';
        position: absolute;
        left: 50px; bottom: -60px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,214,0,0.06);
    }

    .profile-avatar {
        width: 80px; height: 80px;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
        position: relative; z-index: 1;
    }
    .avatar-male { background: rgba(100,130,255,0.25); color: #90caf9; }
    .avatar-female { background: rgba(255,100,150,0.25); color: #f48fb1; }

    .profile-info { flex: 1; position: relative; z-index: 1; }
    .profile-name { font-size: 1.6rem; font-weight: 800; color: white; margin-bottom: 6px; }
    .profile-id { font-size: 0.88rem; color: rgba(255,255,255,0.5); }
    .profile-id strong { color: rgba(255,255,255,0.8); }

    .profile-badges { display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap; }
    .pbadge {
        padding: 5px 14px; border-radius: 20px;
        font-size: 0.78rem; font-weight: 600;
        border: 1px solid rgba(255,255,255,0.15);
        color: rgba(255,255,255,0.8);
        background: rgba(255,255,255,0.08);
    }
    .pbadge-gold { background: rgba(255,214,0,0.15); border-color: rgba(255,214,0,0.3); color: #ffd600; }

    .profile-actions { display: flex; gap: 10px; flex-shrink: 0; position: relative; z-index: 1; }

    .paction-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 18px;
        border-radius: 10px;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.88rem; font-weight: 600;
        text-decoration: none; cursor: pointer;
        transition: all 0.2s; border: none;
    }
    .paction-edit {
        background: rgba(255,214,0,0.15);
        border: 1px solid rgba(255,214,0,0.3);
        color: #ffd600;
    }
    .paction-edit:hover { background: rgba(255,214,0,0.25); color: #ffd600; }
    .paction-back {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        color: rgba(255,255,255,0.7);
    }
    .paction-back:hover { background: rgba(255,255,255,0.15); color: white; }

    /* Info Cards Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    .info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8eaf6;
        box-shadow: 0 2px 12px rgba(26,35,126,0.05);
        overflow: hidden;
    }

    .info-card-header {
        padding: 16px 20px;
        border-bottom: 2px solid #f0f2fa;
        display: flex; align-items: center; gap: 9px;
    }
    .info-card-icon {
        width: 32px; height: 32px;
        border-radius: 9px;
        background: linear-gradient(135deg, #1a237e, #3949ab);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 0.78rem;
    }
    .info-card-title {
        font-size: 0.9rem; font-weight: 700; color: #1a237e;
    }

    .info-card-body { padding: 8px 0; }

    .info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        border-bottom: 1px solid #f8f9ff;
    }
    .info-row:last-child { border-bottom: none; }
    .info-key {
        font-size: 0.82rem; color: #90a4ae; font-weight: 500;
        flex-shrink: 0; margin-left: 10px;
    }
    .info-val {
        font-size: 0.9rem; color: #1a237e; font-weight: 600;
        text-align: left;
    }

    /* Status badges */
    .sb { padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .sb-blue { background: #e3f2fd; color: #1565c0; }
    .sb-rose { background: #fce4ec; color: #880e4f; }
    .sb-green { background: #e8f5e9; color: #2e7d32; }
    .sb-red { background: #ffebee; color: #c62828; }
    .sb-orange { background: #fff3e0; color: #e65100; }

    /* Delete zone */
    .danger-zone {
        background: #fff5f5;
        border: 1.5px solid #ffcdd2;
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 24px;
    }
    .danger-zone-text h6 { color: #c62828; font-weight: 700; margin: 0 0 4px; }
    .danger-zone-text p { color: #ef9a9a; font-size: 0.83rem; margin: 0; }
    .btn-delete {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px;
        background: #ffebee;
        border: 1.5px solid #ef9a9a;
        color: #c62828;
        border-radius: 10px;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.88rem; font-weight: 600;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-delete:hover { background: #e53935; color: white; border-color: #e53935; }

    @media (max-width: 900px) {
        .info-grid { grid-template-columns: 1fr; }
        .profile-header-card { flex-direction: column; align-items: flex-start; }
        .profile-actions { width: 100%; }
    }
</style>

{{-- Profile Header --}}
<div class="profile-header-card">
    <div class="profile-avatar {{ $student->gender == 'male' ? 'avatar-male' : 'avatar-female' }}">
        <i class="fas fa-{{ $student->gender == 'male' ? 'male' : 'female' }}"></i>
    </div>
    <div class="profile-info">
        <div class="profile-name">{{ $student->full_name }}</div>
        <div class="profile-id">رقم الهوية: <strong>{{ $student->IDNumber }}</strong></div>
        <div class="profile-badges">
            <span class="pbadge">{{ $student->gender == 'male' ? '👦 ذكر' : '👧 أنثى' }}</span>
            <span class="pbadge">{{ $student->age }} سنة</span>
            @if($student->gradeByAge)
                <span class="pbadge pbadge-gold">📚 {{ $student->gradeByAge }}</span>
            @endif
            @if($student->OrphanStatus == 'orphan')
                <span class="pbadge" style="background:rgba(239,83,80,0.15);border-color:rgba(239,83,80,0.3);color:#ef9a9a;">يتيم</span>
            @endif
        </div>
    </div>
    <div class="profile-actions">
        <a href="{{ route('students.edit', $student->id) }}" class="paction-btn paction-edit">
            <i class="fas fa-edit"></i> تعديل
        </a>
        <a href="{{ route('students.index') }}" class="paction-btn paction-back">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
</div>

{{-- Info Grid --}}
<div class="info-grid">

    {{-- Card 1: Personal --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-icon"><i class="fas fa-user"></i></div>
            <span class="info-card-title">المعلومات الشخصية</span>
        </div>
        <div class="info-card-body">
            <div class="info-row">
                <span class="info-key">اسم الطالب</span>
                <span class="info-val">{{ $student->studentName }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">اسم الأب</span>
                <span class="info-val">{{ $student->FatherName }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">اسم الجد</span>
                <span class="info-val">{{ $student->GrandfatherName ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">اسم العائلة</span>
                <span class="info-val">{{ $student->lastName }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">تاريخ الميلاد</span>
                <span class="info-val">{{ $student->dateOfBirth->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">العمر</span>
                <span class="info-val">{{ $student->age }} سنة</span>
            </div>
            <div class="info-row">
                <span class="info-key">الجنس</span>
                <span class="info-val">
                    @if($student->gender == 'male')
                        <span class="sb sb-blue">ذكر</span>
                    @else
                        <span class="sb sb-rose">أنثى</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Card 2: Academic & Contact --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-icon"><i class="fas fa-graduation-cap"></i></div>
            <span class="info-card-title">الأكاديمي والتواصل</span>
        </div>
        <div class="info-card-body">
            <div class="info-row">
                <span class="info-key">الصف حسب العمر</span>
                <span class="info-val">{{ $student->gradeByAge ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">آخر شهادة</span>
                <span class="info-val">{{ $student->lastCertificateObtained ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">هاتف ولي الأمر</span>
                <span class="info-val">
                    <a href="tel:{{ $student->Parentmobile }}" style="color:#1565c0;text-decoration:none;">
                        <i class="fas fa-phone-alt fa-xs me-1"></i>{{ $student->Parentmobile }}
                    </a>
                </span>
            </div>
            <div class="info-row">
                <span class="info-key">القريب الولي</span>
                <span class="info-val">{{ $student->RelativeGuardian ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">الحالة الصحية</span>
                <span class="info-val">
                    @if($student->healthCondition == 'Healthy')
                        <span class="sb sb-green">سليم</span>
                    @elseif($student->healthCondition == 'disabled')
                        <span class="sb sb-red">معاق</span>
                    @else
                        <span class="sb sb-orange">مصاب</span>
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-key">حالة اليتم</span>
                <span class="info-val">
                    @if($student->OrphanStatus == 'orphan')
                        <span class="sb sb-red">يتيم</span>
                    @else
                        <span class="sb sb-green">غير يتيم</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Card 3: Registration --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-icon"><i class="fas fa-file-alt"></i></div>
            <span class="info-card-title">معلومات التسجيل</span>
        </div>
        <div class="info-card-body">
            <div class="info-row">
                <span class="info-key">تاريخ التسجيل</span>
                <span class="info-val">{{ $student->registrationDate->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">حالة الدفع</span>
                <span class="info-val">{{ $student->paymentStatus ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">تسجيل الوزارة</span>
                <span class="info-val">{{ $student->RegistrationStatusMinistry ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">تاريخ الإضافة</span>
                <span class="info-val">{{ $student->created_at->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">آخر تعديل</span>
                <span class="info-val">{{ $student->updated_at->format('Y-m-d') }}</span>
            </div>
        </div>
    </div>

</div>

{{-- Danger Zone --}}
<div class="danger-zone">
    <div class="danger-zone-text">
        <h6><i class="fas fa-exclamation-triangle me-2"></i>منطقة الخطر</h6>
        <p>حذف بيانات الطالب نهائياً — لا يمكن التراجع عن هذا الإجراء</p>
    </div>
    <form action="{{ route('students.destroy', $student->id) }}" method="POST"
          onsubmit="return confirm('هل أنت متأكد من حذف بيانات الطالب {{ $student->full_name }}؟')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-delete">
            <i class="fas fa-trash"></i> حذف الطالب
        </button>
    </form>
</div>

@endsection



    