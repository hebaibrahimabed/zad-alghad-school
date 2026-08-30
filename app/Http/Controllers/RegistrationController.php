<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Registration;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentDiscount;
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
     * عرض تفاصيل تسجيل واحد + الخصومات القابلة للإضافة
     */
    public function show(Registration $registration)
    {
        $registration->load(['student.parent', 'schoolClass.level', 'discounts.discount', 'payments']);

        // الخصومات النشطة سارية المفعول حالياً وغير مطبّقة على هذا التسجيل بعد
        $appliedDiscountIds = $registration->discounts->pluck('discount_id');
        $availableDiscounts = Discount::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->whereNotIn('id', $appliedDiscountIds)
            ->orderBy('name')
            ->get();

        return view('registrations.show', compact('registration', 'availableDiscounts'));
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

    /**
     * إضافة خصم يدوياً لتسجيل موجود مسبقاً
     */
    public function addDiscount(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'discount_id' => 'required|exists:discounts,id',
            'reason' => 'required|string|max:255',
        ], [
            'discount_id.required' => 'الرجاء اختيار خصم',
            'reason.required' => 'سبب إضافة الخصم مطلوب',
        ]);

        $discount = Discount::findOrFail($validated['discount_id']);

        // منع تكرار نفس الخصم على نفس التسجيل
        if ($registration->discounts()->where('discount_id', $discount->id)->exists()) {
            return redirect()->back()->with('error', 'هذا الخصم مطبّق على التسجيل مسبقاً.');
        }

        $registration->loadMissing('schoolClass');
        $classPrice = $registration->schoolClass->price ?? 0;
        $appliedValue = $discount->value_type === 'percentage'
            ? round($classPrice * ($discount->value / 100), 2)
            : $discount->value;

        try {
            StudentDiscount::create([
                'registration_id' => $registration->id,
                'discount_id' => $discount->id,
                'applied_value' => $appliedValue,
                'reason' => $validated['reason'],
                'applied_by' => auth()->id(),
            ]);

            return redirect()->route('registrations.show', $registration->id)
                ->with('success', 'تم إضافة الخصم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة الخصم: ' . $e->getMessage());
        }
    }

    /**
     * إزالة خصم مطبّق من تسجيل
     */
    public function removeDiscount(Registration $registration, StudentDiscount $studentDiscount)
    {
        if ($studentDiscount->registration_id !== $registration->id) {
            abort(404);
        }

        try {
            $studentDiscount->delete();
            return redirect()->route('registrations.show', $registration->id)
                ->with('success', 'تم إزالة الخصم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إزالة الخصم: ' . $e->getMessage());
        }
    }
}
