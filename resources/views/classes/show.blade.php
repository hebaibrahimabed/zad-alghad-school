@extends('layouts.zad')


@section('title', 'تفاصيل الشعبة')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $class->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الشعب</a></li>
                    <li class="breadcrumb-item active">تفاصيل</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">

            {{-- بيانات الشعبة --}}
            <div class="col-md-4">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle ml-1"></i> بيانات الشعبة</h3>
                        <div class="card-tools">
                            <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted" width="45%">اسم الشعبة</td>
                                <td><strong>{{ $class->name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">الصف</td>
                                <td><span class="badge badge-info">{{ $class->level->name ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">السنة الدراسية</td>
                                <td>{{ $class->academic_year }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">الرسوم</td>
                                <td><strong class="text-success">{{ number_format($class->price, 2) }} ₪</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">تاريخ البداية</td>
                                <td>{{ $class->start_date->format('Y/m/d') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">تاريخ النهاية</td>
                                <td>{{ $class->end_date->format('Y/m/d') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">الحد الأدنى</td>
                                <td>{{ $class->min_capacity ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">الحد الأقصى</td>
                                <td>{{ $class->max_capacity ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">عدد الطلاب</td>
                                <td>
                                    <span class="badge badge-{{ $class->is_full ? 'danger' : 'success' }} badge-lg">
                                        {{ $class->students_count }}
                                        @if($class->max_capacity) / {{ $class->max_capacity }} @endif
                                    </span>
                                    @if($class->is_full)
                                        <small class="text-danger d-block">الشعبة ممتلئة</small>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('classes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right ml-1"></i> رجوع
                        </a>
                    </div>
                </div>
            </div>

            {{-- قائمة الطلاب --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users ml-1"></i> الطلاب المسجلون
                            <span class="badge badge-primary mr-1">{{ $class->registrations->count() }}</span>
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>رقم الهوية</th>
                                    <th>تاريخ التسجيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($class->registrations as $registration)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $registration->student->full_name ?? '-' }}</td>
                                    <td>{{ $registration->student->national_id ?? '-' }}</td>
                                    <td>{{ $registration->created_at->format('Y/m/d') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                        لا يوجد طلاب مسجلون في هذه الشعبة
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
