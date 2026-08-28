<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * عرض كل تسجيلات طالب معيّن
     */
    public function index(Student $student)
    {
        $registrations = $student->registrations()
            ->with(['schoolClass.level', 'discounts.discount', 'payments'])
            ->orderBy('registration_date', 'desc')
            ->get();

        return view('registrations.index', compact('student', 'registrations'));
    }

    /**
     * عرض تفاصيل تسجيل واحد
     */
    public function show(Registration $registration)
    {
        $registration->load(['student.parent', 'schoolClass.level', 'discounts.discount', 'payments']);
        return view('registrations.show', compact('registration'));
    }

    /**
     * فورم تعديل حالة/بيانات التسجيل
     */
    public function edit(Registration $registration)
    {
        $registration->load('schoolClass');
        return view('registrations.edit', compact('registration'));
    }

    /**
     * تحديث حالة/بيانات التسجيل
     */
    public function update(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'current_status' => 'required|in:active,withdrawn,graduated,transferred',
            'ministry_registration' => 'required|in:pending,registered,exempt',
            'notes' => 'nullable|string',
        ], [
            'current_status.required' => 'حالة التسجيل مطلوبة',
            'ministry_registration.required' => 'حالة تسجيل الوزارة مطلوبة',
        ]);

        try {
            $registration->update($validated);

            return redirect()->route('students.show', $registration->student_id)
                ->with('success', 'تم تحديث حالة التسجيل بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * حذف تسجيل (Soft Delete) — فقط لو ما فيه دفعات مسجّلة عليه
     */
    public function destroy(Registration $registration)
    {
        if ($registration->payments()->exists()) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف هذا التسجيل لوجود دفعات مالية مرتبطة به. يمكنك تغيير حالته إلى "منسحب" بدلاً من الحذف.');
        }

        try {
            $studentId = $registration->student_id;
            $registration->delete();

            return redirect()->route('students.show', $studentId)
                ->with('success', 'تم حذف التسجيل بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
