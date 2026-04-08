@extends('adminlte::page')

@section('title', 'إضافة طالب جديد')

@section('content_header')
    <h1>إضافة طالب جديد</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">نموذج إضافة طالب</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right"></i> رجوع
                        </a>
                    </div>
                </div>
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h5><i class="icon fas fa-ban"></i> خطأ في البيانات!</h5>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Personal Information Section --}}
                        <div class="row">
                            <div class="col-12">
                                <h4 class="text-primary"><i class="fas fa-user"></i> المعلومات الشخصية</h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="IDNumber">رقم الهوية <span class="text-danger">*</span></label>
                                    <input type="text" name="IDNumber" id="IDNumber"
                                           class="form-control @error('IDNumber') is-invalid @enderror"
                                           value="{{ old('IDNumber') }}" required>
                                    @error('IDNumber')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="studentName">اسم الطالب <span class="text-danger">*</span></label>
                                    <input type="text" name="studentName" id="studentName"
                                           class="form-control @error('studentName') is-invalid @enderror"
                                           value="{{ old('studentName') }}" required>
                                    @error('studentName')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="FatherName">اسم الأب <span class="text-danger">*</span></label>
                                    <input type="text" name="FatherName" id="FatherName"
                                           class="form-control @error('FatherName') is-invalid @enderror"
                                           value="{{ old('FatherName') }}" required>
                                    @error('FatherName')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="GrandfatherName">اسم الجد</label>
                                    <input type="text" name="GrandfatherName" id="GrandfatherName"
                                           class="form-control @error('GrandfatherName') is-invalid @enderror"
                                           value="{{ old('GrandfatherName') }}">
                                    @error('GrandfatherName')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lastName">اسم العائلة <span class="text-danger">*</span></label>
                                    <input type="text" name="lastName" id="lastName"
                                           class="form-control @error('lastName') is-invalid @enderror"
                                           value="{{ old('lastName') }}" required>
                                    @error('lastName')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dateOfBirth">تاريخ الميلاد <span class="text-danger">*</span></label>
                                    <input type="date" name="dateOfBirth" id="dateOfBirth"
                                           class="form-control @error('dateOfBirth') is-invalid @enderror"
                                           value="{{ old('dateOfBirth') }}" required>
                                    @error('dateOfBirth')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">الجنس <span class="text-danger">*</span></label>
                                    <select name="gender" id="gender"
                                            class="form-control @error('gender') is-invalid @enderror" required>
                                        <option value="">-- اختر الجنس --</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gradeByAge">الصف حسب العمر</label>
                                    <input type="text" name="gradeByAge" id="gradeByAge"
                                           class="form-control @error('gradeByAge') is-invalid @enderror"
                                           value="{{ old('gradeByAge') }}">
                                    @error('gradeByAge')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastCertificateObtained">آخر شهادة حصل عليها</label>
                                    <input type="text" name="lastCertificateObtained" id="lastCertificateObtained"
                                           class="form-control @error('lastCertificateObtained') is-invalid @enderror"
                                           value="{{ old('lastCertificateObtained') }}">
                                    @error('lastCertificateObtained')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Parentmobile">رقم هاتف ولي الأمر <span class="text-danger">*</span></label>
                                    <input type="text" name="Parentmobile" id="Parentmobile"
                                           class="form-control @error('Parentmobile') is-invalid @enderror"
                                           value="{{ old('Parentmobile') }}"
                                           placeholder="مثال: 0599123456" required>
                                    @error('Parentmobile')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Guardian and Health Information --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4 class="text-primary"><i class="fas fa-heart"></i> معلومات الولي والحالة الصحية</h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="RelativeGuardian">القريب الولي</label>
                                    <input type="text" name="RelativeGuardian" id="RelativeGuardian"
                                           class="form-control @error('RelativeGuardian') is-invalid @enderror"
                                           value="{{ old('RelativeGuardian') }}">
                                    @error('RelativeGuardian')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="healthCondition">الحالة الصحية <span class="text-danger">*</span></label>
                                    <select name="healthCondition" id="healthCondition"
                                            class="form-control @error('healthCondition') is-invalid @enderror" required>
                                        <option value="">-- اختر الحالة الصحية --</option>
                                        <option value="Healthy" {{ old('healthCondition') == 'Healthy' ? 'selected' : '' }}>سليم</option>
                                        <option value="disabled" {{ old('healthCondition') == 'disabled' ? 'selected' : '' }}>معاق</option>
                                        <option value="injured" {{ old('healthCondition') == 'injured' ? 'selected' : '' }}>مصاب</option>
                                    </select>
                                    @error('healthCondition')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="OrphanStatus">حالة اليتم <span class="text-danger">*</span></label>
                                    <select name="OrphanStatus" id="OrphanStatus"
                                            class="form-control @error('OrphanStatus') is-invalid @enderror" required>
                                        <option value="">-- اختر حالة اليتم --</option>
                                        <option value="orphan" {{ old('OrphanStatus') == 'orphan' ? 'selected' : '' }}>يتيم</option>
                                        <option value="not orphan" {{ old('OrphanStatus') == 'not orphan' ? 'selected' : '' }}>غير يتيم</option>
                                    </select>
                                    @error('OrphanStatus')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="registrationDate">تاريخ التسجيل <span class="text-danger">*</span></label>
                                    <input type="date" name="registrationDate" id="registrationDate"
                                           class="form-control @error('registrationDate') is-invalid @enderror"
                                           value="{{ old('registrationDate', date('Y-m-d')) }}" required>
                                    @error('registrationDate')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Registration Status --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4 class="text-primary"><i class="fas fa-file-alt"></i> معلومات التسجيل</h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paymentStatus">حالة الدفع</label>
                                    <input type="text" name="paymentStatus" id="paymentStatus"
                                           class="form-control @error('paymentStatus') is-invalid @enderror"
                                           value="{{ old('paymentStatus') }}">
                                    @error('paymentStatus')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="RegistrationStatusMinistry">حالة التسجيل في الوزارة</label>
                                    <input type="text" name="RegistrationStatusMinistry" id="RegistrationStatusMinistry"
                                           class="form-control @error('RegistrationStatusMinistry') is-invalid @enderror"
                                           value="{{ old('RegistrationStatusMinistry') }}">
                                    @error('RegistrationStatusMinistry')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> حفظ
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .form-group label {
        font-weight: 600;
    }
    .text-danger {
        color: #dc3545;
    }
</style>
@stop
