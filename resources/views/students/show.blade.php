@extends('adminlte::page')

@section('title', 'عرض بيانات الطالب')

@section('content_header')
    <h1>عرض بيانات الطالب</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">بيانات الطالب: {{ $student->full_name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.edit', $student->IDNumber) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Personal Information --}}
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-primary"><i class="fas fa-user"></i> المعلومات الشخصية</h4>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">رقم الهوية</th>
                                    <td>{{ $student->IDNumber }}</td>
                                </tr>
                                <tr>
                                    <th>اسم الطالب</th>
                                    <td>{{ $student->studentName }}</td>
                                </tr>
                                <tr>
                                    <th>اسم الأب</th>
                                    <td>{{ $student->FatherName }}</td>
                                </tr>
                                <tr>
                                    <th>اسم الجد</th>
                                    <td>{{ $student->GrandfatherName ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>اسم العائلة</th>
                                    <td>{{ $student->lastName }}</td>
                                </tr>
                                <tr>
                                    <th>الاسم الكامل</th>
                                    <td><strong>{{ $student->full_name }}</strong></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">تاريخ الميلاد</th>
                                    <td>{{ $student->dateOfBirth->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>العمر</th>
                                    <td>{{ $student->age }} سنة</td>
                                </tr>
                                <tr>
                                    <th>الجنس</th>
                                    <td>
                                        @if($student->gender == 'male')
                                            <span class="badge badge-info">ذكر</span>
                                        @else
                                            <span class="badge badge-warning">أنثى</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>الصف حسب العمر</th>
                                    <td>{{ $student->gradeByAge ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>آخر شهادة</th>
                                    <td>{{ $student->lastCertificateObtained ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Contact Information --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4 class="text-primary"><i class="fas fa-phone"></i> معلومات الاتصال</h4>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">رقم هاتف ولي الأمر</th>
                                    <td>
                                        <a href="tel:{{ $student->Parentmobile }}">
                                            <i class="fas fa-phone-alt text-success"></i> {{ $student->Parentmobile }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>القريب الولي</th>
                                    <td>{{ $student->RelativeGuardian ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">الحالة الصحية</th>
                                    <td>
                                        @if($student->healthCondition == 'Healthy')
                                            <span class="badge badge-success">سليم</span>
                                        @elseif($student->healthCondition == 'disabled')
                                            <span class="badge badge-danger">معاق</span>
                                        @else
                                            <span class="badge badge-warning">مصاب</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>حالة اليتم</th>
                                    <td>
                                        @if($student->OrphanStatus == 'orphan')
                                            <span class="badge badge-danger">يتيم</span>
                                        @else
                                            <span class="badge badge-success">غير يتيم</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Registration Information --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4 class="text-primary"><i class="fas fa-file-alt"></i> معلومات التسجيل</h4>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">تاريخ التسجيل</th>
                                    <td>{{ $student->registrationDate->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>حالة الدفع</th>
                                    <td>{{ $student->paymentStatus ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">حالة التسجيل في الوزارة</th>
                                    <td>{{ $student->RegistrationStatusMinistry ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإضافة للنظام</th>
                                    <td>{{ $student->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('students.edit', $student->IDNumber) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل البيانات
                    </a>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> العودة للقائمة
                    </a>
                    <form action="{{ route('students.destroy', $student->IDNumber) }}"
                          method="POST" style="display: inline-block;"
                          onsubmit="return confirm('هل أنت متأكد من حذف بيانات هذا الطالب؟ لا يمكن التراجع عن هذا الإجراء!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> حذف الطالب
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .card-body h4 {
        margin-top: 20px;
    }
</style>
@stop
