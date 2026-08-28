<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * عرض كل دفعات تسجيل معين + ملخص الرصيد
     */
    public function index(Registration $registration)
    {
        $registration->load(['student', 'schoolClass', 'discounts.discount', 'payments']);

        $totalDiscount = $registration->discounts->sum('applied_value');
        $netFee = max(0, ($registration->schoolClass->price ?? 0) - $totalDiscount);
        $totalPaid = $registration->payments->sum('amount_paid');
        $totalOutstanding = max(0, $netFee - $totalPaid);

        return view('payments.index', compact('registration', 'netFee', 'totalPaid', 'totalOutstanding', 'totalDiscount'));
    }

    /**
     * فورم إضافة دفعة جديدة
     */
    public function create(Registration $registration)
    {
        $registration->load(['schoolClass', 'discounts']);
        return view('payments.create', compact('registration'));
    }

    /**
     * حفظ دفعة جديدة
     */
    public function store(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'amount_due_month' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_at' => 'nullable|date',
            'payment_method' => 'nullable|in:cash,app',
            'notes' => 'nullable|string',
        ], [
            'amount_due_month.required' => 'المبلغ المستحق مطلوب',
            'amount_paid.required' => 'المبلغ المدفوع مطلوب',
            'due_date.required' => 'تاريخ الاستحقاق مطلوب',
        ]);

        // حساب المتبقي وحالة الدفعة تلقائياً
        $outstanding = max(0, $validated['amount_due_month'] - $validated['amount_paid']);
        $status = $validated['amount_paid'] <= 0
            ? 'pending'
            : ($outstanding > 0 ? 'partial' : 'paid');

        try {
            Payment::create([
                'registration_id' => $registration->id,
                'amount_due_month' => $validated['amount_due_month'],
                'total_outstanding' => $outstanding,
                'amount_paid' => $validated['amount_paid'],
                'due_date' => $validated['due_date'],
                'paid_at' => $validated['amount_paid'] > 0 ? ($validated['paid_at'] ?? now()) : null,
                'payment_method' => $validated['payment_method'] ?? null,
                'status' => $status,
                'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()->route('payments.index', $registration->id)
                ->with('success', 'تم تسجيل الدفعة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تسجيل الدفعة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * فورم تعديل دفعة
     */
    public function edit(Payment $payment)
    {
        $payment->load('registration.schoolClass');
        return view('payments.edit', compact('payment'));
    }

    /**
     * تحديث دفعة
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount_due_month' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_at' => 'nullable|date',
            'payment_method' => 'nullable|in:cash,app',
            'notes' => 'nullable|string',
        ]);

        $outstanding = max(0, $validated['amount_due_month'] - $validated['amount_paid']);
        $status = $validated['amount_paid'] <= 0
            ? 'pending'
            : ($outstanding > 0 ? 'partial' : 'paid');

        try {
            $payment->update(array_merge($validated, [
                'total_outstanding' => $outstanding,
                'status' => $status,
                'paid_at' => $validated['amount_paid'] > 0 ? ($validated['paid_at'] ?? $payment->paid_at ?? now()) : null,
            ]));

            return redirect()->route('payments.index', $payment->registration_id)
                ->with('success', 'تم تحديث الدفعة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * حذف دفعة
     */
    public function destroy(Payment $payment)
    {
        try {
            $registrationId = $payment->registration_id;
            $payment->delete();

            return redirect()->route('payments.index', $registrationId)
                ->with('success', 'تم حذف الدفعة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
