@extends('layouts.zad')

@section('title', 'أولياء الأمور')
@section('page-title', 'إدارة أولياء الأمور')

@section('content')
<style>
    .list-card { background: white; border-radius: 16px; border: 1px solid #e8eaf6; box-shadow: 0 2px 12px rgba(26,35,126,0.05); overflow: hidden; }
    .list-toolbar { padding: 20px 24px; border-bottom: 2px solid #f0f2fa; display: flex; gap: 12px; align-items: center; }
    .list-toolbar input { flex: 1; border: 1.5px solid #e8eaf6; border-radius: 10px; padding: 10px 14px; font-family: 'Tajawal', sans-serif; }
    .btn-search { background: linear-gradient(135deg, #0d1257, #3949ab); color: white; border: none; border-radius: 10px; padding: 10px 22px; font-weight: 700; cursor: pointer; }
    table.zad-table { width: 100%; border-collapse: collapse; }
    table.zad-table th { background: #f8f9ff; color: #1a237e; font-size: 0.82rem; font-weight: 700; padding: 12px 16px; text-align: right; border-bottom: 2px solid #e8eaf6; }
    table.zad-table td { padding: 12px 16px; font-size: 0.87rem; color: #37474f; border-bottom: 1px solid #f0f2fa; }
    table.zad-table tr:hover td { background: #f8f9ff; }
    .badge-count { background: #e3f2fd; color: #1565c0; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
    .btn-icon { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1.5px solid #e8eaf6; color:#546e7a; text-decoration:none; margin-left:6px; }
    .btn-icon:hover { background:#3949ab; color:white; border-color:#3949ab; }
    .empty-state { text-align:center; padding: 60px 20px; color: #90a4ae; }
</style>

<div class="list-card">
    <div class="list-toolbar">
        <form action="{{ route('parents.index') }}" method="GET" style="display:flex; gap:12px; flex:1;">
            <input type="text" name="search" placeholder="بحث بالاسم / رقم الهوية / رقم الهاتف..." value="{{ request('search') }}">
            <button type="submit" class="btn-search"><i class="fas fa-search"></i> بحث</button>
        </form>
    </div>

    @if($parents->count() === 0)
        <div class="empty-state">
            <i class="fas fa-user-shield fa-3x" style="margin-bottom:14px;"></i>
            <p>لا يوجد أولياء أمور مطابقين للبحث.</p>
        </div>
    @else
        <table class="zad-table">
            <thead>
                <tr>
                    <th>الاسم الكامل</th>
                    <th>رقم الهوية</th>
                    <th>الهاتف</th>
                    <th>صلة القرابة</th>
                    <th>عدد الأبناء</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $relationLabels = ['father'=>'أب','mother'=>'أم','brother'=>'أخ','sister'=>'أخت','uncle'=>'عم/خال','aunt'=>'عمة/خالة','grandfather'=>'جد','grandmother'=>'جدة','other'=>'أخرى'];
                @endphp
                @foreach($parents as $parent)
                <tr>
                    <td>{{ $parent->first_name }} {{ $parent->second_name }} {{ $parent->third_name }}</td>
                    <td>{{ $parent->national_id }}</td>
                    <td>{{ $parent->phone }}</td>
                    <td>{{ $relationLabels[$parent->relation] ?? $parent->relation }}</td>
                    <td><span class="badge-count">{{ $parent->students_count }}</span></td>
                    <td>
                        <a href="{{ route('parents.show', $parent->id) }}" class="btn-icon" title="عرض"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('parents.edit', $parent->id) }}" class="btn-icon" title="تعديل"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding: 18px 24px;">{{ $parents->links() }}</div>
    @endif
</div>
@endsection
