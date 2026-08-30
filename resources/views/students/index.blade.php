{{-- resources/views/students/index.blade.php --}}
@extends('layouts.zad')

@section('title', 'قائمة الطلاب')
@section('page-title', 'إدارة الطلاب')

@section('content')
<style>
    .search-panel {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(26,35,126,0.05);
        border: 1px solid #e8eaf6;
    }
    .search-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f0f2fa;
    }
    .search-panel-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a237e;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .search-panel-title i { color: #5c6bc0; }

    .search-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 14px;
    }
    .search-grid-2 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }

    .field-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #546e7a;
        margin-bottom: 6px;
    }
    .field-group .form-control,
    .field-group .form-select {
        border: 1.5px solid #e8eaf6;
        border-radius: 10px;
        padding: 9px 12px;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.88rem;
        color: #1a237e;
        background: #f8f9ff;
        transition: border-color 0.2s;
        width: 100%;
    }
    .field-group .form-control:focus,
    .field-group .form-select:focus {
        border-color: #3949ab;
        background: white;
        box-shadow: 0 0 0 3px rgba(57,73,171,0.08);
        outline: none;
    }

    .search-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    .btn-search {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 22px;
        background: linear-gradient(135deg, #0d1257, #3949ab);
        color: white;
        border: none;
        border-radius: 10px;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-search:hover { transform: translateY(-1px); box-shadow: 0 5px 15px rgba(26,35,126,0.3); }

    .btn-clear {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 18px;
        background: white;
        color: #78909c;
        border: 1.5px solid #e8eaf6;
        border-radius: 10px;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-clear:hover { border-color: #ef5350; color: #ef5350; }

    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }
    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #e8eaf6;
        color: #3949ab;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .filter-tag .remove { cursor: pointer; color: #9e9e9e; font-size: 0.7rem; }
    .filter-tag .remove:hover { color: #ef5350; }

    /* Table */
    .table-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(26,35,126,0.05);
        border: 1px solid #e8eaf6;
    }
    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f0f2fa;
    }
    .table-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a237e;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .table-count {
        background: #e8eaf6;
        color: #3949ab;
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .add-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 18px;
        background: linear-gradient(135deg, #0d1257, #3949ab);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .add-btn:hover { color: white; transform: translateY(-1px); box-shadow: 0 5px 15px rgba(26,35,126,0.3); }

    .students-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .students-table thead th {
        background: #f0f2fa;
        color: #1a237e;
        font-weight: 700;
        padding: 12px 14px;
        font-size: 0.84rem;
        text-align: right;
        border: none;
        white-space: nowrap;
    }
    .students-table thead th:first-child { border-radius: 10px 0 0 10px; }
    .students-table thead th:last-child { border-radius: 0 10px 10px 0; }
    .students-table tbody td {
        padding: 13px 14px;
        border-bottom: 1px solid #f0f2fa;
        font-size: 0.88rem;
        color: #37474f;
        vertical-align: middle;
    }
    .students-table tbody tr:last-child td { border-bottom: none; }
    .students-table tbody tr:hover td { background: #f8f9ff; }

    .badge-male { background: #e3f2fd; color: #1565c0; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .badge-female { background: #fce4ec; color: #880e4f; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .badge-healthy { background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .badge-disabled { background: #ffebee; color: #c62828; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .badge-injured { background: #fff3e0; color: #e65100; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }

    .action-group { display: flex; gap: 6px; }
    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-view { background: #e3f2fd; color: #1565c0; }
    .btn-edit { background: #fff8e1; color: #e65100; }
    .btn-del  { background: #ffebee; color: #c62828; }
    .btn-icon:hover { transform: scale(1.1); }

    .empty-state { text-align: center; padding: 50px 20px; color: #9e9e9e; }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; color: #c5cae9; }
    .empty-state p { font-size: 1rem; }

    .pagination-wrap { margin-top: 20px; }
    .pagination-wrap .page-link {
        border-radius: 8px;
        margin: 0 2px;
        color: #3949ab;
        border: 1.5px solid #e8eaf6;
        font-family: 'Tajawal', sans-serif;
    }
    .pagination-wrap .page-item.active .page-link {
        background: linear-gradient(135deg, #1a237e, #3949ab);
        border-color: transparent;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 14px 18px;
        margin-bottom: 16px;
        font-size: 0.9rem;
    }
    .alert-success { background: #e8f5e9; color: #2e7d32; }
    .alert-danger { background: #ffebee; color: #c62828; }
</style>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
@endif

{{-- Advanced Search Panel --}}
<div class="search-panel">
    <div class="search-panel-header">
        <div class="search-panel-title">
            <i class="fas fa-filter"></i>
            البحث والتصفية
        </div>
        @php
            $hasFilters = collect(request()->only(['studentName','FatherName','GrandfatherName','lastName','IDNumber','Parentmobile','gradeByAge','lastCertificate','gender','healthCondition','dateFrom','dateTo']))->filter()->isNotEmpty();
        @endphp
        @if($hasFilters)
            <a href="{{ route('students.index') }}" class="btn-clear">
                <i class="fas fa-times"></i> مسح الفلاتر
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('students.index') }}">
        {{-- Row 1: Name fields --}}
        <div class="search-grid">
            <div class="field-group">
                <label><i class="fas fa-user fa-xs"></i> اسم الطالب</label>
                <input type="text" name="studentName" class="form-control"
                       placeholder="ابحث باسم الطالب..."
                       value="{{ request('studentName') }}">
            </div>
            <div class="field-group">
                <label><i class="fas fa-user fa-xs"></i> اسم الأب</label>
                <input type="text" name="FatherName" class="form-control"
                       placeholder="ابحث باسم الأب..."
                       value="{{ request('FatherName') }}">
            </div>
            <div class="field-group">
                <label><i class="fas fa-user fa-xs"></i> اسم الجد</label>
                <input type="text" name="GrandfatherName" class="form-control"
                       placeholder="ابحث باسم الجد..."
                       value="{{ request('GrandfatherName') }}">
            </div>
            <div class="field-group">
                <label><i class="fas fa-user fa-xs"></i> اسم العائلة</label>
                <input type="text" name="lastName" class="form-control"
                       placeholder="ابحث باسم العائلة..."
                       value="{{ request('lastName') }}">
            </div>
        </div>

        {{-- Row 2: IDs, Phone, Grade --}}
        <div class="search-grid">
            <div class="field-group">
                <label><i class="fas fa-id-card fa-xs"></i> رقم الهوية</label>
                <input type="text" name="IDNumber" class="form-control"
                       placeholder="رقم الهوية (9 أرقام)..."
                       value="{{ request('IDNumber') }}">
            </div>
            <div class="field-group">
                <label><i class="fas fa-phone fa-xs"></i> رقم الهاتف</label>
                <input type="text" name="Parentmobile" class="form-control"
                       placeholder="رقم هاتف ولي الأمر..."
                       value="{{ request('Parentmobile') }}">
            </div>
            <div class="field-group">
                <label><i class="fas fa-graduation-cap fa-xs"></i> الصف الدراسي</label>
                <input type="text" name="gradeByAge" class="form-control"
                       placeholder="الصف حسب العمر..."
                       value="{{ request('gradeByAge') }}">
            </div>
            <div class="field-group">
                <label><i class="fas fa-certificate fa-xs"></i> آخر شهادة</label>
                <input type="text" name="lastCertificate" class="form-control"
                       placeholder="آخر شهادة حصل عليها..."
                       value="{{ request('lastCertificate') }}">
            </div>
        </div>

        {{-- Row 3: Dropdowns --}}
        <div class="search-grid">
            <div class="field-group">
                <label><i class="fas fa-venus-mars fa-xs"></i> الجنس</label>
                <select name="gender" class="form-select">
                    <option value="">الكل</option>
                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                </select>
            </div>
            <div class="field-group">
                <label><i class="fas fa-heartbeat fa-xs"></i> الحالة الصحية</label>
                <select name="healthCondition" class="form-select">
                    <option value="">الكل</option>
                    <option value="Healthy" {{ request('healthCondition') == 'Healthy' ? 'selected' : '' }}>سليم</option>
                    <option value="disabled" {{ request('healthCondition') == 'disabled' ? 'selected' : '' }}>معاق</option>
                    <option value="injured" {{ request('healthCondition') == 'injured' ? 'selected' : '' }}>مصاب</option>
                </select>
            </div>
        </div>

        {{-- Row 4: Date range --}}
        {{-- ملاحظة: واجهات parents/payments/registrations صارت موجودة الآن (راجع صفحة الطالب → زر "التسجيلات")،
             لكن فلترة قائمة الطلاب هنا حسب حالة الدفع/اليتم/تسجيل الوزارة لم تُربط بعد
             بهذه العلاقات — تحسين مستقبلي مقترح --}}
        <div class="search-grid-2" style="margin-top:14px;">
            <div class="field-group">
                <label><i class="fas fa-calendar fa-xs"></i> تاريخ التسجيل (من)</label>
                <input type="date" name="dateFrom" class="form-control" value="{{ request('dateFrom') }}">
            </div>
            <div class="field-group">
                <label><i class="fas fa-calendar fa-xs"></i> تاريخ التسجيل (إلى)</label>
                <input type="date" name="dateTo" class="form-control" value="{{ request('dateTo') }}">
            </div>
        </div>

        <div class="search-actions">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> بحث
            </button>
            <a href="{{ route('students.index') }}" class="btn-clear">
                <i class="fas fa-redo"></i> إعادة تعيين
            </a>
        </div>

        {{-- Active Filters Tags --}}
        @if($hasFilters)
        <div class="active-filters">
            @foreach(['studentName'=>'اسم الطالب','FatherName'=>'اسم الأب','GrandfatherName'=>'اسم الجد','lastName'=>'العائلة','IDNumber'=>'الهوية','Parentmobile'=>'الهاتف','gradeByAge'=>'الصف','lastCertificate'=>'الشهادة','gender'=>'الجنس','healthCondition'=>'الصحة','dateFrom'=>'من تاريخ','dateTo'=>'إلى تاريخ'] as $key => $label)
                @if(request($key))
                    <span class="filter-tag">
                        {{ $label }}: {{ request($key) }}
                    </span>
                @endif
            @endforeach
        </div>
        @endif
    </form>
</div>

{{-- Students Table --}}
<div class="table-card">
    <div class="table-header">
        <div class="table-title">
            <i class="fas fa-users"></i>
            قائمة الطلاب
            <span class="table-count">{{ $students->total() }} طالب</span>
        </div>
           <div class="header-actions" style="display: flex; gap: 10px;">
            {{-- زر التصدير الجديد --}}
            <a href="{{ route('students.export-excel', request()->query()) }}" class="btn-clear" style="border-color: #2e7d32; color: #2e7d32;">
                <i class="fas fa-file-excel"></i> تصدير Excel
            </a>

            <a href="{{ route('students.register') }}" class="add-btn">
                <i class="fas fa-plus"></i> إضافة طالب
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="students-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>رقم الهوية</th>
                    <th>الاسم الكامل</th>
                    <th>الجنس</th>
                    <th>تاريخ الميلاد</th>
                    <th>الصف</th>
                    <th>رقم الهاتف</th>
                    <th>الحالة الصحية</th>
                    <th>تاريخ التسجيل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                <tr>
                    <td style="color:#9e9e9e;font-size:0.8rem;">{{ $students->firstItem() + $i }}</td>
                    <td><strong style="color:#1a237e;">{{ $student->IDNumber }}</strong></td>
                    <td>{{ $student->full_name }}</td>
                    <td>
                        @if($student->gender == 'male')
                            <span class="badge-male"><i class="fas fa-mars fa-xs me-1"></i>ذكر</span>
                        @else
                            <span class="badge-female"><i class="fas fa-venus fa-xs me-1"></i>أنثى</span>
                        @endif
                    </td>
                    <td>{{ $student->dateOfBirth->format('Y-m-d') }}</td>
                    <td>{{ $student->gradeByAge ?? '—' }}</td>
                    <td dir="ltr" style="text-align:right;">{{ $student->Parentmobile }}</td>
                    <td>
                        @if($student->healthCondition == 'Healthy')
                            <span class="badge-healthy">سليم</span>
                        @elseif($student->healthCondition == 'disabled')
                            <span class="badge-disabled">معاق</span>
                        @else
                            <span class="badge-injured">مصاب</span>
                        @endif
                    </td>
                    <td>{{ $student->registrationDate->format('Y-m-d') }}</td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('students.show', $student->id) }}" class="btn-icon btn-view" title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn-icon btn-edit" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-del" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11">
                        <div class="empty-state">
                            <i class="fas fa-users-slash"></i>
                            <p>لا توجد نتائج مطابقة للبحث</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $students->appends(request()->query())->links() }}
    </div>
</div>

<script>
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 4000);
</script>
@endsection
