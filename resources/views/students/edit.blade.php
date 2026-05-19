{{-- resources/views/students/create.blade.php  (وكذلك edit.blade.php بنفس البنية) --}}
@extends('layouts.zad')

@section('title', isset($student) ? 'تعديل بيانات الطالب' : 'إضافة طالب جديد')
@section('page-title', isset($student) ? 'تعديل بيانات الطالب' : 'إضافة طالب جديد')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8eaf6;
        box-shadow: 0 2px 12px rgba(26,35,126,0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .form-card-header {
        padding: 18px 24px;
        border-bottom: 2px solid #f0f2fa;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card-header .section-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #1a237e, #3949ab);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 0.85rem;
    }

    .form-card-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #1a237e;
    }

    .form-card-body { padding: 24px; }

    .field-row {
        display: grid;
        gap: 18px;
        margin-bottom: 18px;
    }
    .cols-2 { grid-template-columns: 1fr 1fr; }
    .cols-3 { grid-template-columns: 1fr 1fr 1fr; }
    .cols-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }

    .field-wrap label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.84rem;
        font-weight: 600;
        color: #546e7a;
        margin-bottom: 8px;
    }

    .field-wrap label .req { color: #e53935; font-size: 0.75rem; }
    .field-wrap label i { color: #9e9e9e; font-size: 0.78rem; }

    .field-wrap input,
    .field-wrap select,
    .field-wrap textarea {
        width: 100%;
        border: 2px solid #e8eaf6;
        border-radius: 11px;
        padding: 11px 14px;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.9rem;
        color: #1a237e;
        background: #f8f9ff;
        transition: all 0.25s;
        outline: none;
    }

    .field-wrap input:focus,
    .field-wrap select:focus,
    .field-wrap textarea:focus {
        border-color: #3949ab;
        background: white;
        box-shadow: 0 0 0 4px rgba(57,73,171,0.08);
    }

    .field-wrap input[readonly] {
        background: #eceff1;
        color: #90a4ae;
        cursor: not-allowed;
    }

    .field-wrap .invalid-feedback {
        display: block;
        font-size: 0.78rem;
        color: #e53935;
        margin-top: 5px;
    }

    .field-wrap input.is-invalid,
    .field-wrap select.is-invalid {
        border-color: #e53935;
        background: #fff5f5;
    }

    .field-wrap .hint {
        font-size: 0.75rem;
        color: #9e9e9e;
        margin-top: 4px;
    }

    /* Action bar */
    .action-bar {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8eaf6;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-save {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #0d1257, #3949ab);
        color: white; border: none; border-radius: 11px;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.95rem; font-weight: 700;
        cursor: pointer; transition: all 0.25s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(26,35,126,0.3);
    }

    .btn-cancel {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 22px;
        background: white; color: #546e7a;
        border: 2px solid #e8eaf6; border-radius: 11px;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.9rem; font-weight: 600;
        text-decoration: none; transition: all 0.2s;
    }

    .btn-cancel:hover {
        border-color: #78909c;
        color: #37474f;
    }

    /* Errors summary */
    .errors-box {
        background: #ffebee;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        border-right: 4px solid #e53935;
    }

    .errors-box h6 {
        color: #c62828;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .errors-box ul {
        margin: 0; padding-right: 18px;
        color: #c62828; font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .cols-2, .cols-3, .cols-4 { grid-template-columns: 1fr; }
    }
</style>

@if($errors->any())
<div class="errors-box">
    <h6><i class="fas fa-exclamation-triangle me-2"></i>يوجد أخطاء في البيانات:</h6>
    <ul>
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ isset($student) ? route('students.update', $student->IDNumber) : route('students.store') }}" method="POST">
    @csrf
    @if(isset($student)) @method('PUT') @endif

    {{-- SECTION 1: Personal Info --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="section-icon"><i class="fas fa-user"></i></div>
            <h5>المعلومات الشخصية</h5>
        </div>
        <div class="form-card-body">

            <div class="field-row cols-2">
                <div class="field-wrap">
                    <label>
                        <i class="fas fa-id-card"></i>
                        رقم الهوية
                        <span class="req">*</span>
                    </label>
                    <input type="text" name="IDNumber"
                           class="@error('IDNumber') is-invalid @enderror"
                           value="{{ old('IDNumber', $student->IDNumber ?? '') }}"
                           {{ isset($student) ? 'readonly' : 'required' }}
                           placeholder="9 أرقام">
                    @error('IDNumber')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    @if(isset($student))
                        <div class="hint"><i class="fas fa-lock fa-xs"></i> لا يمكن تعديل رقم الهوية</div>
                    @endif
                </div>

                <div class="field-wrap">
                    <label>
                        <i class="fas fa-user"></i>
                        اسم الطالب
                        <span class="req">*</span>
                    </label>
                    <input type="text" name="studentName"
                           class="@error('studentName') is-invalid @enderror"
                           value="{{ old('studentName', $student->studentName ?? '') }}"
                           required placeholder="الاسم الأول">
                    @error('studentName')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="field-row cols-3">
                <div class="field-wrap">
                    <label><i class="fas fa-user-tie"></i> اسم الأب <span class="req">*</span></label>
                    <input type="text" name="FatherName"
                           class="@error('FatherName') is-invalid @enderror"
                           value="{{ old('FatherName', $student->FatherName ?? '') }}"
                           required placeholder="اسم الأب">
                    @error('FatherName')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label><i class="fas fa-user-tie"></i> اسم الجد</label>
                    <input type="text" name="GrandfatherName"
                           class="@error('GrandfatherName') is-invalid @enderror"
                           value="{{ old('GrandfatherName', $student->GrandfatherName ?? '') }}"
                           placeholder="اسم الجد (اختياري)">
                    @error('GrandfatherName')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label><i class="fas fa-users"></i> اسم العائلة <span class="req">*</span></label>
                    <input type="text" name="lastName"
                           class="@error('lastName') is-invalid @enderror"
                           value="{{ old('lastName', $student->lastName ?? '') }}"
                           required placeholder="اسم العائلة">
                    @error('lastName')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="field-row cols-3">
                <div class="field-wrap">
                    <label><i class="fas fa-calendar-alt"></i> تاريخ الميلاد <span class="req">*</span></label>
                    <input type="date" name="dateOfBirth"
                           class="@error('dateOfBirth') is-invalid @enderror"
                           value="{{ old('dateOfBirth', isset($student) ? $student->dateOfBirth->format('Y-m-d') : '') }}"
                           required>
                    @error('dateOfBirth')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label><i class="fas fa-venus-mars"></i> الجنس <span class="req">*</span></label>
                    <select name="gender" class="@error('gender') is-invalid @enderror" required>
                        <option value="">-- اختر الجنس --</option>
                        <option value="male" {{ old('gender', $student->gender ?? '') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                    @error('gender')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- <div class="field-wrap">
                    <label><i class="fas fa-graduation-cap"></i> الصف حسب العمر</label>
                    <input type="text" name="gradeByAge"
                           class="@error('gradeByAge') is-invalid @enderror"
                           value="{{ old('gradeByAge', $student->gradeByAge ?? '') }}"
                           placeholder="مثال: الثالث الابتدائي">
                    @error('gradeByAge')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div> --}}
            </div>

            <div class="field-row cols-2">
                <div class="field-wrap">
                    <label><i class="fas fa-certificate"></i> آخر شهادة حصل عليها</label>
                    <input type="text" name="lastCertificateObtained"
                           class="@error('lastCertificateObtained') is-invalid @enderror"
                           value="{{ old('lastCertificateObtained', $student->lastCertificateObtained ?? '') }}"
                           placeholder="مثال: شهادة الصف الثاني">
                    @error('lastCertificateObtained')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label><i class="fas fa-phone"></i> رقم هاتف ولي الأمر <span class="req">*</span></label>
                    <input type="text" name="Parentmobile"
                           class="@error('Parentmobile') is-invalid @enderror"
                           value="{{ old('Parentmobile', $student->Parentmobile ?? '') }}"
                           required placeholder="مثال: 0599123456">
                    @error('Parentmobile')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

        </div>
    </div>

    {{-- SECTION 2: Guardian & Health --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="section-icon"><i class="fas fa-heartbeat"></i></div>
            <h5>معلومات الولي والحالة الصحية</h5>
        </div>
        <div class="form-card-body">
            <div class="field-row cols-3">
                <div class="field-wrap">
                    <label><i class="fas fa-user-shield"></i> القريب الولي</label>
                    <input type="text" name="RelativeGuardian"
                           class="@error('RelativeGuardian') is-invalid @enderror"
                           value="{{ old('RelativeGuardian', $student->RelativeGuardian ?? '') }}"
                           placeholder="اسم القريب الولي">
                    @error('RelativeGuardian')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label><i class="fas fa-heartbeat"></i> الحالة الصحية <span class="req">*</span></label>
                    <select name="healthCondition" class="@error('healthCondition') is-invalid @enderror" required>
                        <option value="">-- اختر الحالة الصحية --</option>
                        <option value="Healthy" {{ old('healthCondition', $student->healthCondition ?? '') == 'Healthy' ? 'selected' : '' }}>سليم</option>
                        <option value="disabled" {{ old('healthCondition', $student->healthCondition ?? '') == 'disabled' ? 'selected' : '' }}>معاق</option>
                        <option value="injured" {{ old('healthCondition', $student->healthCondition ?? '') == 'injured' ? 'selected' : '' }}>مصاب</option>
                    </select>
                    @error('healthCondition')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label><i class="fas fa-child"></i> حالة اليتم <span class="req">*</span></label>
                    <select name="OrphanStatus" class="@error('OrphanStatus') is-invalid @enderror" required>
                        <option value="">-- اختر حالة اليتم --</option>
                        <option value="orphan" {{ old('OrphanStatus', $student->OrphanStatus ?? '') == 'orphan' ? 'selected' : '' }}>يتيم</option>
                        <option value="not orphan" {{ old('OrphanStatus', $student->OrphanStatus ?? '') == 'not orphan' ? 'selected' : '' }}>غير يتيم</option>
                    </select>
                    @error('OrphanStatus')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: Registration --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="section-icon"><i class="fas fa-file-alt"></i></div>
            <h5>معلومات التسجيل</h5>
        </div>
        <div class="form-card-body">
            <div class="field-row cols-3">
                <div class="field-wrap">
                    <label><i class="fas fa-calendar-check"></i> تاريخ التسجيل <span class="req">*</span></label>
                    <input type="date" name="registrationDate"
                           class="@error('registrationDate') is-invalid @enderror"
                           value="{{ old('registrationDate', isset($student) ? $student->registrationDate->format('Y-m-d') : date('Y-m-d')) }}"
                           required>
                    @error('registrationDate')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label><i class="fas fa-money-bill-wave"></i> حالة الدفع</label>
                    <input type="text" name="paymentStatus"
                           class="@error('paymentStatus') is-invalid @enderror"
                           value="{{ old('paymentStatus', $student->paymentStatus ?? '') }}"
                           placeholder="حالة الدفع">
                    @error('paymentStatus')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label><i class="fas fa-building"></i> حالة التسجيل في الوزارة</label>
                    <input type="text" name="RegistrationStatusMinistry"
                           class="@error('RegistrationStatusMinistry') is-invalid @enderror"
                           value="{{ old('RegistrationStatusMinistry', $student->RegistrationStatusMinistry ?? '') }}"
                           placeholder="حالة تسجيل الوزارة">
                    @error('RegistrationStatusMinistry')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ACTION BAR --}}
    <div class="action-bar">
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i>
            {{ isset($student) ? 'تحديث البيانات' : 'حفظ الطالب' }}
        </button>
        <a href="{{ route('students.index') }}" class="btn-cancel">
            <i class="fas fa-times"></i> إلغاء
        </a>
    </div>

</form>
@endsection
