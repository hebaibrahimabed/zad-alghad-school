@extends('adminlte::page')

@section('title', 'قائمة الطلاب')

@section('content_header')
    <h1>إدارة الطلاب</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة الطلاب</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> إضافة طالب جديد
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Search and Filter Form --}}
                    <form method="GET" action="{{ route('students.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control"
                                           placeholder="البحث برقم الهوية، الاسم، أو رقم الهاتف..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="gender" class="form-control">
                                        <option value="">الجنس - الكل</option>
                                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="healthCondition" class="form-control">
                                        <option value="">الحالة الصحية - الكل</option>
                                        <option value="Healthy" {{ request('healthCondition') == 'Healthy' ? 'selected' : '' }}>سليم</option>
                                        <option value="disabled" {{ request('healthCondition') == 'disabled' ? 'selected' : '' }}>معاق</option>
                                        <option value="injured" {{ request('healthCondition') == 'injured' ? 'selected' : '' }}>مصاب</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="OrphanStatus" class="form-control">
                                        <option value="">حالة اليتم - الكل</option>
                                        <option value="orphan" {{ request('OrphanStatus') == 'orphan' ? 'selected' : '' }}>يتيم</option>
                                        <option value="not orphan" {{ request('OrphanStatus') == 'not orphan' ? 'selected' : '' }}>غير يتيم</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Students Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>رقم الهوية</th>
                                    <th>الاسم الكامل</th>
                                    <th>الجنس</th>
                                    <th>تاريخ الميلاد</th>
                                    <th>رقم الهاتف</th>
                                    <th>حالة اليتم</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->IDNumber }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td>
                                            @if($student->gender == 'male')
                                                <span class="badge badge-info">ذكر</span>
                                            @else
                                                <span class="badge badge-warning">أنثى</span>
                                            @endif
                                        </td>
                                        <td>{{ $student->dateOfBirth->format('Y-m-d') }}</td>
                                        <td>{{ $student->Parentmobile }}</td>
                                        <td>
                                            @if($student->OrphanStatus == 'orphan')
                                                <span class="badge badge-danger">يتيم</span>
                                            @else
                                                <span class="badge badge-success">غير يتيم</span>
                                            @endif
                                        </td>
                                        <td>{{ $student->registrationDate->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('students.show', $student->IDNumber) }}"
                                                   class="btn btn-info btn-sm" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('students.edit', $student->IDNumber) }}"
                                                   class="btn btn-warning btn-sm" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('students.destroy', $student->IDNumber) }}"
                                                      method="POST" style="display: inline-block;"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <i class="fas fa-info-circle"></i> لا توجد بيانات
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table th {
        text-align: center;
        vertical-align: middle;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@stop

@section('js')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@stop
