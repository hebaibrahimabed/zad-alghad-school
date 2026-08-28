<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = Discount::withCount('studentDiscounts');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $discounts = $query->orderBy('is_active', 'desc')->orderBy('name')->get();

        return view('discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('discounts.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateDiscount($request);

        try {
            Discount::create($validated);
            return redirect()->route('discounts.index')->with('success', 'تم إضافة الخصم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Discount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $validated = $this->validateDiscount($request);

        try {
            $discount->update($validated);
            return redirect()->route('discounts.index')->with('success', 'تم تحديث الخصم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * حذف الخصم — فقط لو ما انطبق على أي طالب لحفظ سجل تاريخي صحيح للدفعات القديمة.
     * البديل الآمن: تعطيله (is_active = false).
     */
    public function destroy(Discount $discount)
    {
        if ($discount->studentDiscounts()->exists()) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف هذا الخصم لأنه مطبّق على طلاب فعلياً. يمكنك تعطيله بدلاً من ذلك.');
        }

        try {
            $discount->delete();
            return redirect()->route('discounts.index')->with('success', 'تم حذف الخصم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }

    /**
     * تبديل حالة التفعيل بسرعة من القائمة
     */
    public function toggleActive(Discount $discount)
    {
        $discount->update(['is_active' => !$discount->is_active]);
        return redirect()->back()->with('success', $discount->is_active ? 'تم تفعيل الخصم' : 'تم تعطيل الخصم');
    }

    private function validateDiscount(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:general,special',
            'value' => 'required|numeric|min:0',
            'value_type' => 'required|in:percentage,fixed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'اسم الخصم مطلوب',
            'type.required' => 'نوع الخصم مطلوب',
            'value.required' => 'قيمة الخصم مطلوبة',
            'value_type.required' => 'طريقة احتساب الخصم مطلوبة',
            'start_date.required' => 'تاريخ بداية سريان الخصم مطلوب',
            'end_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ البداية',
        ]);

        // checkbox لا يُرسَل أصلاً بالـ request لو غير محدد، فلازم نتعامل معه صراحة
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
