<?php

namespace App\Http\Controllers\students;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRegistrationRequest;
use App\Models\Discount;
use App\Models\ParentModel;
use App\Models\Registration;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentRegistrationController extends Controller
{
    /**
     * عرض معالج التسجيل (Wizard)
     */
    public function create()
    {
        $levels = \App\Models\Level::where('is_active', true)->orderBy('name')->get();
        $classes = SchoolClass::with('level')
            ->where('end_date', '>=', now())
            ->orderBy('academic_year', 'desc')
            ->orderBy('name')
            ->get();

        return view('students.register-wizard', compact('levels', 'classes'));
    }

    /**
     * البحث عن ولي أمر موجود مسبقاً برقم الهوية (AJAX)
     */
    public function lookupParent(Request $request)
    {
        $request->validate([
            'national_id' => 'required|string|max:20',
        ]);

        $parent = ParentModel::where('national_id', $request->national_id)->first();

        if (!$parent) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'parent' => [
                'id' => $parent->id,
                'first_name' => $parent->first_name,
                'second_name' => $parent->second_name,
                'third_name' => $parent->third_name,
                'gender' => $parent->gender,
                'birth_date' => optional($parent->birth_date)->format('Y-m-d'),
                'phone' => $parent->phone,
                'relation' => $parent->relation,
                'address' => $parent->address,
                'housing_status' => $parent->housing_status,
                'work' => $parent->work,
                'orphan_status_student' => $parent->orphan_status_student,
            ],
        ]);
    }

    /**
     * التحقق التلقائي من استحقاق خصم الإخوة + عرض الخصومات الخاصة المتاحة (AJAX)
     *
     * القاعدة: نفس رقم هوية ولي الأمر + نفس السنة الدراسية لصف مسجّل به طالب آخر فعلاً
     */
    public function checkDiscounts(Request $request)
    {
        $request->validate([
            'national_id' => 'required|string|max:20',
            'class_id' => 'required|exists:classes,id',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $isSibling = $this->hasSiblingInSameAcademicYear($request->national_id, $class->academic_year);

        $generalDiscounts = $isSibling
            ? Discount::where('type', 'general')
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->get(['id', 'name', 'value', 'value_type'])
            : collect();

        $specialDiscounts = Discount::where('type', 'special')
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->get(['id', 'name', 'value', 'value_type']);

        return response()->json([
            'is_sibling' => $isSibling,
            'general_discounts' => $generalDiscounts,
            'special_discounts' => $specialDiscounts,
        ]);
    }

    /**
     * حفظ كل بيانات التسجيل دفعة واحدة (طالب + ولي أمر + تسجيل + خصومات)
     */
    public function store(StoreStudentRegistrationRequest $request)
    {
        $data = $request->validated();

        try {
            $result = DB::transaction(function () use ($data) {
                // 1) إيجاد ولي الأمر أو إنشاؤه (لو موجود برقم الهوية بيتحدّث ببياناته الجديدة)
                $parent = ParentModel::updateOrCreate(
                    ['national_id' => $data['parent']['national_id']],
                    $data['parent']
                );

                // 2) إنشاء الطالب مرتبط بولي الأمر
                $studentData = $data['student'];
                $studentData['parent_id'] = $parent->id;
                $studentData['gradeByAge'] = Student::determineGradeByAge($studentData['dateOfBirth']);
                $studentData['Parentmobile'] = $parent->phone;
                $studentData['registrationDate'] = $data['registration']['registration_date'];

                $student = Student::create($studentData);

                // 3) إنشاء التسجيل بالشعبة
                $class = SchoolClass::findOrFail($data['registration']['class_id']);
                $registration = Registration::create([
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'registration_date' => $data['registration']['registration_date'],
                    'ministry_registration' => $data['registration']['ministry_registration'],
                    'current_status' => 'active',
                    'notes' => $data['registration']['notes'] ?? null,
                ]);

                // 4) خصم الإخوة التلقائي (يُعاد التحقق منه هنا من السيرفر، لا يُعتمد على الواجهة)
                $appliedDiscounts = [];
                if ($this->hasSiblingInSameAcademicYear($parent->national_id, $class->academic_year, $student->id)) {
                    $generalDiscounts = Discount::where('type', 'general')
                        ->where('is_active', true)
                        ->where('start_date', '<=', now())
                        ->where(function ($q) {
                            $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                        })
                        ->get();

                    foreach ($generalDiscounts as $discount) {
                        $appliedValue = $discount->value_type === 'percentage'
                            ? round($class->price * ($discount->value / 100), 2)
                            : $discount->value;

                        StudentDiscount::create([
                            'registration_id' => $registration->id,
                            'discount_id' => $discount->id,
                            'applied_value' => $appliedValue,
                            'reason' => 'خصم إخوة تلقائي',
                            'applied_by' => auth()->id(),
                        ]);
                        $appliedDiscounts[] = $discount->name;
                    }
                }

                // 5) الخصومات الخاصة المختارة يدوياً
                foreach ($data['discounts']['special'] ?? [] as $special) {
                    $discount = Discount::find($special['discount_id']);
                    if (!$discount || $discount->type !== 'special' || !$discount->is_active) {
                        continue;
                    }

                    $appliedValue = $discount->value_type === 'percentage'
                        ? round($class->price * ($discount->value / 100), 2)
                        : $discount->value;

                    StudentDiscount::create([
                        'registration_id' => $registration->id,
                        'discount_id' => $discount->id,
                        'applied_value' => $appliedValue,
                        'reason' => $special['reason'],
                        'applied_by' => auth()->id(),
                    ]);
                    $appliedDiscounts[] = $discount->name;
                }

                return [$student, $appliedDiscounts];
            });

            [$student, $appliedDiscounts] = $result;

            $message = 'تم تسجيل الطالب بنجاح.';
            if (!empty($appliedDiscounts)) {
                $message .= ' تم تطبيق الخصومات التالية: ' . implode('، ', $appliedDiscounts);
            }

            return redirect()->route('students.show', $student->id)->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('فشل تسجيل طالب جديد (Wizard): ' . $e->getMessage());

            return back()->withInput()->with('error', 'حدث خطأ أثناء حفظ بيانات التسجيل، الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * هل يوجد طالب آخر لنفس ولي الأمر (برقم الهوية) مسجّل فعلياً بنفس السنة الدراسية؟
     */
    private function hasSiblingInSameAcademicYear(string $parentNationalId, string $academicYear, ?int $excludeStudentId = null): bool
    {
        $parent = ParentModel::where('national_id', $parentNationalId)->first();
        if (!$parent) {
            return false;
        }

        $query = Registration::whereHas('student', function ($q) use ($parent, $excludeStudentId) {
                $q->where('parent_id', $parent->id);
                if ($excludeStudentId) {
                    $q->where('id', '!=', $excludeStudentId);
                }
            })
            ->whereHas('schoolClass', function ($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            })
            ->where('current_status', 'active');

        return $query->exists();
    }
}
