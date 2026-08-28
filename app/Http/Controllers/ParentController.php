<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateParentRequest;
use App\Models\ParentModel;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Display a listing of parents.
     */
    public function index(Request $request)
    {
        $query = ParentModel::withCount('students');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('second_name', 'like', "%{$search}%")
                  ->orWhere('third_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $parents = $query->orderBy('first_name')->paginate(30);

        return view('parents.index', compact('parents'));
    }

    /**
     * Display the specified parent with all linked students.
     */
    public function show(string $id)
    {
        $parent = ParentModel::with(['students.registrations.schoolClass'])->findOrFail($id);
        return view('parents.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified parent.
     */
    public function edit(string $id)
    {
        $parent = ParentModel::findOrFail($id);
        return view('parents.edit', compact('parent'));
    }

    /**
     * Update the specified parent in storage.
     */
    public function update(UpdateParentRequest $request, string $id)
    {
        $parent = ParentModel::findOrFail($id);

        try {
            $parent->update($request->validated());

            return redirect()->route('parents.show', $parent->id)
                ->with('success', 'تم تحديث بيانات ولي الأمر بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified parent (only if no linked students).
     */
    public function destroy(string $id)
    {
        $parent = ParentModel::withCount('students')->findOrFail($id);

        if ($parent->students_count > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف ولي الأمر لوجود طلاب مرتبطين به. الرجاء نقل الطلاب لولي أمر آخر أولاً.');
        }

        try {
            $parent->delete();
            return redirect()->route('parents.index')
                ->with('success', 'تم حذف ولي الأمر بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
