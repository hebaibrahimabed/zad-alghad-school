@extends('layouts.zad')

@section('title', 'إدارة الشعب الدراسية')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">الشعب الدراسية</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">الشعب الدراسية</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <i class="fas fa-check-circle ml-1"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <i class="fas fa-exclamation-circle ml-1"></i> {{ session('error') }}
        </div>
        @endif

        {{-- Filter Card --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter ml-1"></i> فلترة وبحث</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('classes.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>اسم الشعبة</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="ابحث..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>الصف الدراسي</label>
                                <select name="level_id" class="form-control">
                                    <option value="">-- كل الصفوف --</option>
                                    @foreach($levels as $level)
                                    <option value="{{ $level->id }}"
                                        {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>السنة الدراسية</label>
                                <select name="academic_year" class="form-control">
                                    <option value="">-- كل السنوات --</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year }}"
                                        {{ request('academic_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search ml-1"></i> بحث
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'level_id', 'academic_year']))
                    <a href="{{ route('classes.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-times ml-1"></i> إلغاء الفلتر
                    </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chalkboard ml-1"></i> قائمة الشعب
                    <span class="badge badge-primary mr-2">{{ $classes->total() }}</span>
                </h3>
                <div class="card-tools">
                    <a href="{{ route('classes.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus ml-1"></i> إضافة شعبة
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">#</th>
                                <th>اسم الشعبة</th>
                                <th>الصف</th>
                                <th>السنة الدراسية</th>
                                <th>الرسوم</th>
                                <th>تاريخ البداية</th>
                                <th>تاريخ النهاية</th>
                                <th>الطلاب / الطاقة</th>
                                <th width="130">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $class)
                            <tr>
                                <td>{{ $loop->iteration + ($classes->currentPage() - 1) * $classes->perPage() }}</td>
                                <td><strong>{{ $class->name }}</strong></td>
                                <td><span class="badge badge-info">{{ $class->level->name ?? '-' }}</span></td>
                                <td>{{ $class->academic_year }}</td>
                                <td>{{ number_format($class->price, 2) }} ₪</td>
                                <td>{{ $class->start_date->format('Y/m/d') }}</td>
                                <td>{{ $class->end_date->format('Y/m/d') }}</td>
                                <td>
                                    @php $count = $class->students_count; @endphp
                                    <span class="badge badge-{{ $class->is_full ? 'danger' : 'success' }}">
                                        {{ $count }}
                                        @if($class->max_capacity)
                                            / {{ $class->max_capacity }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('classes.show', $class) }}" class="btn btn-sm btn-info" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $class->id }}"
                                        data-name="{{ $class->name }}"
                                        data-count="{{ $class->students_count }}"
                                        title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    لا توجد شعب دراسية
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($classes->hasPages())
            <div class="card-footer">{{ $classes->links() }}</div>
            @endif
        </div>

    </div>
</section>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle ml-1"></i> تأكيد الحذف</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف الشعبة: <strong id="className"></strong>؟</p>
                <p class="text-muted small">لا يمكن التراجع عن هذه العملية.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash ml-1"></i> حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
        const count = parseInt(this.dataset.count);
        if (count > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'لا يمكن الحذف',
                text: `الشعبة "${this.dataset.name}" تحتوي على ${count} طالب مسجل.`,
                confirmButtonText: 'حسناً'
            });
            return;
        }
        document.getElementById('className').textContent = this.dataset.name;
        document.getElementById('deleteForm').action = `/classes/${this.dataset.id}`;
        $('#deleteModal').modal('show');
    });
});
</script>
@endpush
