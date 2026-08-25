@extends('layouts.zad')

@section('title', 'تسجيل طالب جديد')
@section('page-title', 'تسجيل طالب جديد')

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
        display: flex; align-items: center; gap: 10px;
    }
    .form-card-header .section-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: linear-gradient(135deg, #1a237e, #3949ab);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 0.85rem;
    }
    .form-card-header h5 { margin: 0; font-size: 1rem; font-weight: 700; color: #1a237e; }
    .form-card-body { padding: 24px; }

    .field-row { display: grid; gap: 18px; margin-bottom: 18px; }
    .cols-2 { grid-template-columns: 1fr 1fr; }
    .cols-3 { grid-template-columns: 1fr 1fr 1fr; }

    .field-wrap label {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.84rem; font-weight: 600; color: #546e7a; margin-bottom: 8px;
    }
    .field-wrap label .req { color: #e53935; font-size: 0.75rem; }
    .field-wrap input, .field-wrap select, .field-wrap textarea {
        width: 100%; border: 2px solid #e8eaf6; border-radius: 11px;
        padding: 11px 14px; font-family: 'Tajawal', sans-serif;
        font-size: 0.9rem; color: #1a237e; background: #f8f9ff;
        transition: all 0.25s; outline: none;
    }
    .field-wrap input:focus, .field-wrap select:focus, .field-wrap textarea:focus {
        border-color: #3949ab; background: white; box-shadow: 0 0 0 4px rgba(57,73,171,0.08);
    }
    .field-wrap input[readonly] { background: #eceff1; color: #90a4ae; }
    .field-wrap .invalid-feedback { display: block; font-size: 0.78rem; color: #e53935; margin-top: 5px; }
    .field-wrap input.is-invalid, .field-wrap select.is-invalid { border-color: #e53935; background: #fff5f5; }
    .field-wrap .hint { font-size: 0.75rem; color: #9e9e9e; margin-top: 4px; }

    /* Stepper */
    .wizard-steps {
        display: flex; justify-content: space-between; margin-bottom: 24px;
        background: white; border-radius: 16px; padding: 20px 24px;
        border: 1px solid #e8eaf6; box-shadow: 0 2px 12px rgba(26,35,126,0.05);
    }
    .wizard-step { display: flex; align-items: center; gap: 10px; flex: 1; position: relative; }
    .wizard-step:not(:last-child)::after {
        content: ''; position: absolute; top: 18px; right: -50%; width: 100%;
        height: 3px; background: #e8eaf6; z-index: 0;
    }
    .wizard-step.done:not(:last-child)::after { background: #43a047; }
    .step-circle {
        width: 38px; height: 38px; border-radius: 50%; background: #e8eaf6;
        color: #90a4ae; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.95rem; z-index: 1; flex-shrink: 0;
        transition: all 0.25s;
    }
    .wizard-step.active .step-circle { background: linear-gradient(135deg,#1a237e,#3949ab); color: white; }
    .wizard-step.done .step-circle { background: #43a047; color: white; }
    .step-label { font-size: 0.82rem; font-weight: 700; color: #90a4ae; }
    .wizard-step.active .step-label { color: #1a237e; }
    .wizard-step.done .step-label { color: #43a047; }

    .wizard-panel { display: none; }
    .wizard-panel.active { display: block; }

    .action-bar {
        background: white; border-radius: 16px; border: 1px solid #e8eaf6;
        padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; gap: 12px;
    }
    .btn-save, .btn-next, .btn-prev {
        display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px;
        border: none; border-radius: 11px; font-family: 'Tajawal', sans-serif;
        font-size: 0.95rem; font-weight: 700; cursor: pointer; transition: all 0.25s;
    }
    .btn-save, .btn-next { background: linear-gradient(135deg, #0d1257, #3949ab); color: white; }
    .btn-save:hover, .btn-next:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(26,35,126,0.3); }
    .btn-prev { background: white; color: #546e7a; border: 2px solid #e8eaf6; }
    .btn-prev:hover { border-color: #78909c; color: #37474f; }
    .btn-prev:disabled { opacity: 0.4; cursor: not-allowed; }

    .lookup-box { display: flex; gap: 10px; align-items: flex-end; }
    .lookup-box .field-wrap { flex: 1; }
    .btn-lookup {
        padding: 11px 18px; border-radius: 11px; border: 2px solid #3949ab;
        background: white; color: #3949ab; font-weight: 700; cursor: pointer;
        white-space: nowrap; height: 44px;
    }
    .btn-lookup:hover { background: #3949ab; color: white; }
    .btn-lookup:disabled { opacity: 0.5; cursor: not-allowed; }

    .alert-box {
        border-radius: 12px; padding: 14px 18px; margin-bottom: 18px;
        font-size: 0.87rem; font-weight: 600; display: none; align-items: center; gap: 10px;
    }
    .alert-box.show { display: flex; }
    .alert-info { background: #e3f2fd; color: #1565c0; }
    .alert-success { background: #e8f5e9; color: #2e7d32; }
    .alert-warning { background: #fff8e1; color: #f9a825; }

    .discount-card {
        border: 2px solid #e8eaf6; border-radius: 12px; padding: 14px 16px; margin-bottom: 12px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
    }
    .discount-card .d-name { font-weight: 700; color: #1a237e; font-size: 0.9rem; }
    .discount-card .d-value { font-size: 0.78rem; color: #546e7a; }

    .review-box { background: #f8f9ff; border-radius: 12px; padding: 16px 20px; margin-bottom: 14px; }
    .review-box h6 { color: #1a237e; font-weight: 700; margin-bottom: 10px; font-size: 0.9rem; }
    .review-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.85rem; color: #546e7a; }
    .review-row b { color: #1a237e; }

    .errors-box {
        background: #ffebee; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px;
        border-right: 4px solid #e53935;
    }
    .errors-box h6 { color: #c62828; font-weight: 700; font-size: 0.9rem; margin-bottom: 8px; }
    .errors-box ul { margin: 0; padding-right: 18px; color: #c62828; font-size: 0.85rem; }

    @media (max-width: 768px) {
        .cols-2, .cols-3 { grid-template-columns: 1fr; }
        .wizard-steps { overflow-x: auto; }
        .step-label { display: none; }
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

@if(session('error'))
<div class="errors-box">
    <h6><i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}</h6>
</div>
@endif

{{-- Stepper --}}
<div class="wizard-steps" id="wizardSteps">
    <div class="wizard-step active" data-step="1">
        <div class="step-circle">1</div><span class="step-label">بيانات الطالب</span>
    </div>
    <div class="wizard-step" data-step="2">
        <div class="step-circle">2</div><span class="step-label">بيانات ولي الأمر</span>
    </div>
    <div class="wizard-step" data-step="3">
        <div class="step-circle">3</div><span class="step-label">بيانات التسجيل</span>
    </div>
    <div class="wizard-step" data-step="4">
        <div class="step-circle">4</div><span class="step-label">الخصومات والمراجعة</span>
    </div>
</div>

<form action="{{ route('students.register.store') }}" method="POST" id="wizardForm">
    @csrf

    {{-- ============ STEP 1: بيانات الطالب ============ --}}
    <div class="wizard-panel active" data-panel="1">
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon"><i class="fas fa-user-graduate"></i></div>
                <h5>بيانات الطالب</h5>
            </div>
            <div class="form-card-body">
                <div class="field-row cols-2">
                    <div class="field-wrap">
                        <label><i class="fas fa-id-card"></i> رقم هوية الطالب <span class="req">*</span></label>
                        <input type="text" name="student[IDNumber]" value="{{ old('student.IDNumber') }}" required placeholder="9 أرقام">
                    </div>
                    <div class="field-wrap">
                        <label><i class="fas fa-user"></i> اسم الطالب <span class="req">*</span></label>
                        <input type="text" name="student[studentName]" value="{{ old('student.studentName') }}" required>
                    </div>
                </div>
                <div class="field-row cols-3">
                    <div class="field-wrap">
                        <label><i class="fas fa-user-tie"></i> اسم الأب <span class="req">*</span></label>
                        <input type="text" name="student[FatherName]" value="{{ old('student.FatherName') }}" required>
                    </div>
                    <div class="field-wrap">
                        <label><i class="fas fa-user-tie"></i> اسم الجد</label>
                        <input type="text" name="student[GrandfatherName]" value="{{ old('student.GrandfatherName') }}">
                    </div>
                    <div class="field-wrap">
                        <label><i class="fas fa-users"></i> اسم العائلة <span class="req">*</span></label>
                        <input type="text" name="student[lastName]" value="{{ old('student.lastName') }}" required>
                    </div>
                </div>
                <div class="field-row cols-3">
                    <div class="field-wrap">
                        <label><i class="fas fa-calendar-alt"></i> تاريخ الميلاد <span class="req">*</span></label>
                        <input type="date" name="student[dateOfBirth]" value="{{ old('student.dateOfBirth') }}" required>
                    </div>
                    <div class="field-wrap">
                        <label><i class="fas fa-venus-mars"></i> الجنس <span class="req">*</span></label>
                        <select name="student[gender]" required>
                            <option value="">-- اختر --</option>
                            <option value="male" {{ old('student.gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ old('student.gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>
                    <div class="field-wrap">
                        <label><i class="fas fa-heartbeat"></i> الحالة الصحية <span class="req">*</span></label>
                        <select name="student[healthCondition]" required>
                            <option value="">-- اختر --</option>
                            <option value="Healthy" {{ old('student.healthCondition') == 'Healthy' ? 'selected' : '' }}>سليم</option>
                            <option value="disabled" {{ old('student.healthCondition') == 'disabled' ? 'selected' : '' }}>ذوي إعاقة</option>
                            <option value="injured" {{ old('student.healthCondition') == 'injured' ? 'selected' : '' }}>مصاب</option>
                        </select>
                    </div>
                </div>
                <div class="field-row cols-2">
                    <div class="field-wrap">
                        <label><i class="fas fa-certificate"></i> آخر شهادة حصل عليها</label>
                        <input type="text" name="student[lastCertificateObtained]" value="{{ old('student.lastCertificateObtained') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ STEP 2: بيانات ولي الأمر ============ --}}
    <div class="wizard-panel" data-panel="2">
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon"><i class="fas fa-user-shield"></i></div>
                <h5>بيانات ولي الأمر</h5>
            </div>
            <div class="form-card-body">

                <div id="parentFoundAlert" class="alert-box alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>ولي الأمر موجود مسبقاً بالنظام — تم تعبئة بياناته تلقائياً، يمكنك التعديل عليها إذا لزم.</span>
                </div>
                <div id="parentNotFoundAlert" class="alert-box alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>لا يوجد ولي أمر بهذا الرقم — يرجى إدخال بياناته لأول مرة.</span>
                </div>

                <div class="lookup-box" style="margin-bottom:18px;">
                    <div class="field-wrap">
                        <label><i class="fas fa-id-card"></i> رقم هوية ولي الأمر <span class="req">*</span></label>
                        <input type="text" name="parent[national_id]" id="parentNationalId"
                               value="{{ old('parent.national_id') }}" required placeholder="9 أرقام">
                    </div>
                    <button type="button" class="btn-lookup" id="btnLookupParent">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </div>

                <div class="field-row cols-3">
                    <div class="field-wrap">
                        <label>الاسم الأول <span class="req">*</span></label>
                        <input type="text" name="parent[first_name]" id="p_first_name" value="{{ old('parent.first_name') }}" required>
                    </div>
                    <div class="field-wrap">
                        <label>اسم الأب <span class="req">*</span></label>
                        <input type="text" name="parent[second_name]" id="p_second_name" value="{{ old('parent.second_name') }}" required>
                    </div>
                    <div class="field-wrap">
                        <label>اسم العائلة <span class="req">*</span></label>
                        <input type="text" name="parent[third_name]" id="p_third_name" value="{{ old('parent.third_name') }}" required>
                    </div>
                </div>
                <div class="field-row cols-3">
                    <div class="field-wrap">
                        <label>الجنس <span class="req">*</span></label>
                        <select name="parent[gender]" id="p_gender" required>
                            <option value="">-- اختر --</option>
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>
                    <div class="field-wrap">
                        <label>تاريخ الميلاد</label>
                        <input type="date" name="parent[birth_date]" id="p_birth_date" value="{{ old('parent.birth_date') }}">
                    </div>
                    <div class="field-wrap">
                        <label>رقم الهاتف <span class="req">*</span></label>
                        <input type="text" name="parent[phone]" id="p_phone" value="{{ old('parent.phone') }}" required placeholder="0599123456">
                    </div>
                </div>
                <div class="field-row cols-3">
                    <div class="field-wrap">
                        <label>صلة القرابة بالطالب <span class="req">*</span></label>
                        <select name="parent[relation]" id="p_relation" required>
                            <option value="">-- اختر --</option>
                            <option value="father">أب</option>
                            <option value="mother">أم</option>
                            <option value="brother">أخ</option>
                            <option value="sister">أخت</option>
                            <option value="uncle">عم/خال</option>
                            <option value="aunt">عمة/خالة</option>
                            <option value="grandfather">جد</option>
                            <option value="grandmother">جدة</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="field-wrap">
                        <label>الحالة السكنية</label>
                        <select name="parent[housing_status]" id="p_housing_status">
                            <option value="">-- اختر --</option>
                            <option value="owned">ملك</option>
                            <option value="rented">إيجار</option>
                            <option value="tent">خيمة</option>
                            <option value="displaced">نازح</option>
                        </select>
                    </div>
                    <div class="field-wrap">
                        <label>العمل</label>
                        <input type="text" name="parent[work]" id="p_work" value="{{ old('parent.work') }}">
                    </div>
                </div>
                <div class="field-row cols-2">
                    <div class="field-wrap">
                        <label>العنوان</label>
                        <input type="text" name="parent[address]" id="p_address" value="{{ old('parent.address') }}">
                    </div>
                    <div class="field-wrap">
                        <label>حالة يتم الطالب <span class="req">*</span></label>
                        <select name="parent[orphan_status_student]" id="p_orphan_status" required>
                            <option value="not_orphan">غير يتيم</option>
                            <option value="father">يتيم الأب</option>
                            <option value="mother">يتيم الأم</option>
                            <option value="both">يتيم الأبوين</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ STEP 3: بيانات التسجيل ============ --}}
    <div class="wizard-panel" data-panel="3">
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon"><i class="fas fa-file-alt"></i></div>
                <h5>بيانات التسجيل</h5>
            </div>
            <div class="form-card-body">
                <div class="field-row cols-2">
                    <div class="field-wrap">
                        <label><i class="fas fa-layer-group"></i> الشعبة الدراسية <span class="req">*</span></label>
                        <select name="registration[class_id]" id="classSelect" required>
                            <option value="">-- اختر الشعبة --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"
                                        data-academic-year="{{ $class->academic_year }}"
                                        data-price="{{ $class->price }}"
                                        {{ old('registration.class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }} — {{ $class->level->name ?? '' }} ({{ $class->academic_year }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-wrap">
                        <label><i class="fas fa-calendar-check"></i> تاريخ التسجيل <span class="req">*</span></label>
                        <input type="date" name="registration[registration_date]"
                               value="{{ old('registration.registration_date', date('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="field-row cols-2">
                    <div class="field-wrap">
                        <label><i class="fas fa-building"></i> حالة التسجيل بالوزارة <span class="req">*</span></label>
                        <select name="registration[ministry_registration]" required>
                            <option value="pending">قيد الإجراء</option>
                            <option value="registered">مسجّل</option>
                            <option value="exempt">معفى</option>
                        </select>
                    </div>
                </div>
                <div class="field-row cols-2">
                    <div class="field-wrap">
                        <label><i class="fas fa-sticky-note"></i> ملاحظات</label>
                        <textarea name="registration[notes]" rows="2">{{ old('registration.notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ STEP 4: الخصومات والمراجعة ============ --}}
    <div class="wizard-panel" data-panel="4">
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon"><i class="fas fa-percent"></i></div>
                <h5>الخصومات</h5>
            </div>
            <div class="form-card-body">

                <div id="siblingAlert" class="alert-box alert-success">
                    <i class="fas fa-users"></i>
                    <span>الطالب مؤهل لخصم الإخوة تلقائياً (يوجد أخ/أخت مسجّل بنفس السنة الدراسية) — سيُطبّق تلقائياً عند الحفظ.</span>
                </div>
                <div id="noSiblingAlert" class="alert-box alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>لا يوجد خصم إخوة تلقائي لهذا الطالب.</span>
                </div>

                <div id="generalDiscountsList"></div>

                <hr style="margin: 20px 0; border-color:#e8eaf6;">

                <h6 style="color:#1a237e; font-weight:700; font-size:0.9rem; margin-bottom:12px;">
                    <i class="fas fa-hand-holding-usd"></i> خصومات خاصة (اختياري — تُختار يدوياً)
                </h6>
                <div id="specialDiscountsContainer">
                    <p class="hint">اختر الشعبة أولاً بالخطوة السابقة لعرض الخصومات الخاصة المتاحة.</p>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon"><i class="fas fa-clipboard-check"></i></div>
                <h5>مراجعة نهائية</h5>
            </div>
            <div class="form-card-body">
                <div class="review-box">
                    <h6>ملخص التسجيل</h6>
                    <div class="review-row"><span>الطالب:</span> <b id="rvStudentName">—</b></div>
                    <div class="review-row"><span>رقم هوية الطالب:</span> <b id="rvStudentId">—</b></div>
                    <div class="review-row"><span>ولي الأمر:</span> <b id="rvParentName">—</b></div>
                    <div class="review-row"><span>الشعبة:</span> <b id="rvClass">—</b></div>
                    <div class="review-row"><span>تاريخ التسجيل:</span> <b id="rvDate">—</b></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="action-bar">
        <button type="button" class="btn-prev" id="btnPrev" disabled>
            <i class="fas fa-arrow-right"></i> السابق
        </button>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('students.index') }}" class="btn-prev" style="text-decoration:none;">
                <i class="fas fa-times"></i> إلغاء
            </a>
            <button type="button" class="btn-next" id="btnNext">
                التالي <i class="fas fa-arrow-left"></i>
            </button>
            <button type="submit" class="btn-save" id="btnSubmit" style="display:none;">
                <i class="fas fa-save"></i> حفظ التسجيل
            </button>
        </div>
    </div>
</form>

<script>
(function () {
    const totalSteps = 4;
    let currentStep = 1;

    const steps = document.querySelectorAll('.wizard-step');
    const panels = document.querySelectorAll('.wizard-panel');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    const btnSubmit = document.getElementById('btnSubmit');

    function goToStep(step) {
        currentStep = step;
        panels.forEach(p => p.classList.toggle('active', +p.dataset.panel === step));
        steps.forEach(s => {
            const n = +s.dataset.step;
            s.classList.toggle('active', n === step);
            s.classList.toggle('done', n < step);
        });
        btnPrev.disabled = step === 1;
        btnNext.style.display = step === totalSteps ? 'none' : 'inline-flex';
        btnSubmit.style.display = step === totalSteps ? 'inline-flex' : 'none';

        if (step === 4) {
            updateReview();
            fetchDiscounts();
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        const panel = document.querySelector(`.wizard-panel[data-panel="${step}"]`);
        const requiredFields = panel.querySelectorAll('[required]');
        for (const field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                field.classList.add('is-invalid');
                alert('الرجاء تعبئة جميع الحقول المطلوبة (*) قبل المتابعة.');
                return false;
            }
            field.classList.remove('is-invalid');
        }
        return true;
    }

    btnNext.addEventListener('click', function () {
        if (!validateStep(currentStep)) return;
        if (currentStep < totalSteps) goToStep(currentStep + 1);
    });

    btnPrev.addEventListener('click', function () {
        if (currentStep > 1) goToStep(currentStep - 1);
    });

    // ===== بحث ولي الأمر (AJAX) =====
    const btnLookup = document.getElementById('btnLookupParent');
    const parentFields = ['first_name','second_name','third_name','gender','birth_date','phone','relation','housing_status','work','address','orphan_status_student'];

    btnLookup.addEventListener('click', function () {
        const nationalId = document.getElementById('parentNationalId').value.trim();
        if (!nationalId) {
            alert('الرجاء إدخال رقم هوية ولي الأمر أولاً.');
            return;
        }
        btnLookup.disabled = true;
        btnLookup.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`{{ route('parents.lookup') }}?national_id=${encodeURIComponent(nationalId)}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('parentFoundAlert').classList.toggle('show', data.found);
                document.getElementById('parentNotFoundAlert').classList.toggle('show', !data.found);

                if (data.found) {
                    const p = data.parent;
                    parentFields.forEach(f => {
                        const el = document.getElementById('p_' + f);
                        if (el && p[f] !== null && p[f] !== undefined) el.value = p[f];
                    });
                }
            })
            .catch(() => alert('حدث خطأ أثناء البحث، الرجاء المحاولة مرة أخرى.'))
            .finally(() => {
                btnLookup.disabled = false;
                btnLookup.innerHTML = '<i class="fas fa-search"></i> بحث';
            });
    });

    // ===== الخصومات (تُجلب عند الوصول للخطوة 4) =====
    function fetchDiscounts() {
        const nationalId = document.getElementById('parentNationalId').value.trim();
        const classSelect = document.getElementById('classSelect');
        const classId = classSelect.value;

        if (!nationalId || !classId) return;

        fetch(`{{ route('students.register.check-discounts') }}?national_id=${encodeURIComponent(nationalId)}&class_id=${classId}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('siblingAlert').classList.toggle('show', data.is_sibling);
                document.getElementById('noSiblingAlert').classList.toggle('show', !data.is_sibling);

                const generalList = document.getElementById('generalDiscountsList');
                generalList.innerHTML = '';
                data.general_discounts.forEach(d => {
                    const valueLabel = d.value_type === 'percentage' ? `${d.value}%` : `${d.value} ₪`;
                    generalList.innerHTML += `
                        <div class="discount-card">
                            <div><div class="d-name"><i class="fas fa-tag"></i> ${d.name}</div>
                            <div class="d-value">خصم عام — يُطبّق تلقائياً</div></div>
                            <div class="d-value" style="font-weight:700;color:#43a047;">${valueLabel}</div>
                        </div>`;
                });

                const specialContainer = document.getElementById('specialDiscountsContainer');
                if (data.special_discounts.length === 0) {
                    specialContainer.innerHTML = '<p class="hint">لا توجد خصومات خاصة متاحة حالياً.</p>';
                    return;
                }
                specialContainer.innerHTML = '';
                data.special_discounts.forEach((d, idx) => {
                    const valueLabel = d.value_type === 'percentage' ? `${d.value}%` : `${d.value} ₪`;
                    specialContainer.innerHTML += `
                        <div class="discount-card" style="align-items:flex-start; flex-direction:column;">
                            <div style="display:flex; justify-content:space-between; width:100%; align-items:center;">
                                <label style="display:flex; align-items:center; gap:8px; margin:0; cursor:pointer;">
                                    <input type="checkbox" class="special-discount-check" data-id="${d.id}" style="width:auto;">
                                    <span class="d-name">${d.name}</span>
                                </label>
                                <span class="d-value" style="font-weight:700;color:#3949ab;">${valueLabel}</span>
                            </div>
                            <div class="special-reason-wrap" style="display:none; width:100%; margin-top:10px;">
                                <input type="text" class="special-reason-input" placeholder="سبب الخصم (مطلوب)" style="width:100%; border:2px solid #e8eaf6; border-radius:8px; padding:8px 12px; font-family:'Tajawal',sans-serif;">
                            </div>
                        </div>`;
                });

                document.querySelectorAll('.special-discount-check').forEach(chk => {
                    chk.addEventListener('change', function () {
                        this.closest('.discount-card').querySelector('.special-reason-wrap').style.display =
                            this.checked ? 'block' : 'none';
                        syncSpecialDiscountInputs();
                    });
                });
            })
            .catch(() => {});
    }

    // بناء حقول hidden لإرسال الخصومات الخاصة المختارة مع الفورم
    function syncSpecialDiscountInputs() {
        document.querySelectorAll('.hidden-special-input').forEach(el => el.remove());
        const form = document.getElementById('wizardForm');
        let i = 0;
        document.querySelectorAll('.special-discount-check:checked').forEach(chk => {
            const reason = chk.closest('.discount-card').querySelector('.special-reason-input').value;
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.className = 'hidden-special-input';
            idInput.name = `discounts[special][${i}][discount_id]`;
            idInput.value = chk.dataset.id;
            form.appendChild(idInput);

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.className = 'hidden-special-input';
            reasonInput.name = `discounts[special][${i}][reason]`;
            reasonInput.value = reason;
            form.appendChild(reasonInput);
            i++;
        });
    }
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('special-reason-input')) syncSpecialDiscountInputs();
    });

    // sync قبل الإرسال النهائي (يضمن آخر حالة محدّثة للخصومات الخاصة)
    document.getElementById('wizardForm').addEventListener('submit', function (e) {
        syncSpecialDiscountInputs();
        const uncheckedReasons = document.querySelectorAll('.special-discount-check:checked');
        for (const chk of uncheckedReasons) {
            const reason = chk.closest('.discount-card').querySelector('.special-reason-input').value.trim();
            if (!reason) {
                e.preventDefault();
                alert('الرجاء إدخال سبب لكل خصم خاص مختار.');
                return;
            }
        }
    });

    // ===== ملخص المراجعة =====
    function updateReview() {
        const val = (name) => {
            const el = document.querySelector(`[name="${name}"]`);
            return el ? el.value : '';
        };
        const studentName = [val('student[studentName]'), val('student[FatherName]'), val('student[lastName]')].filter(Boolean).join(' ');
        document.getElementById('rvStudentName').textContent = studentName || '—';
        document.getElementById('rvStudentId').textContent = val('student[IDNumber]') || '—';

        const parentName = [val('parent[first_name]'), val('parent[second_name]'), val('parent[third_name]')].filter(Boolean).join(' ');
        document.getElementById('rvParentName').textContent = parentName || '—';

        const classSelect = document.getElementById('classSelect');
        document.getElementById('rvClass').textContent = classSelect.options[classSelect.selectedIndex]?.text || '—';
        document.getElementById('rvDate').textContent = val('registration[registration_date]') || '—';
    }
})();
</script>
@endsection
