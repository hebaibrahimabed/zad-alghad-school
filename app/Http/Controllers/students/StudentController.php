<?php

namespace App\Http\Controllers\students;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('IDNumber', 'like', "%{$search}%")
                  ->orWhere('studentName', 'like', "%{$search}%")
                  ->orWhere('FatherName', 'like', "%{$search}%")
                  ->orWhere('lastName', 'like', "%{$search}%")
                   ->orWhere('gradeByAge', 'like', "%{$search}%")
                    ->orWhere('paymentStatus', 'like', "%{$search}%")
                     ->orWhere('RegistrationStatusMinistry', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender != '') {
            $query->where('gender', $request->gender);
        }

        // Filter by health condition
        if ($request->has('healthCondition') && $request->healthCondition != '') {
            $query->where('healthCondition', $request->healthCondition);
        }

        // Filter by orphan status
        if ($request->has('OrphanStatus') && $request->OrphanStatus != '') {
            $query->where('OrphanStatus', $request->OrphanStatus);
        }

        $students = $query->orderBy('registrationDate', 'desc')->paginate(10);

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            Student::validationRules(),
            Student::validationMessages()
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

            // تأكد من تحويل التواريخ بشكل صحيح
            if (isset($data['dateOfBirth'])) {
                $data['dateOfBirth'] = date('Y-m-d', strtotime($data['dateOfBirth']));
            }

            if (isset($data['registrationDate'])) {
                $data['registrationDate'] = date('Y-m-d', strtotime($data['registrationDate']));
            }

            Student::create($data);

            return redirect()->route('students.index')
                ->with('success', 'تم إضافة الطالب بنجاح');
        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة الطالب: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            Student::validationRules($id),
            Student::validationMessages()
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

            // تأكد من تحويل التواريخ بشكل صحيح
            if (isset($data['dateOfBirth'])) {
                $data['dateOfBirth'] = date('Y-m-d', strtotime($data['dateOfBirth']));
            }

            if (isset($data['registrationDate'])) {
                $data['registrationDate'] = date('Y-m-d', strtotime($data['registrationDate']));
            }

            $student->update($data);

            return redirect()->route('students.index')
                ->with('success', 'تم تحديث بيانات الطالب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return redirect()->route('students.index')
                ->with('success', 'تم حذف الطالب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الطالب: ' . $e->getMessage());
        }
    }

    /**
     * Export students to Excel
     */
    public function export()
    {
        // يمكن إضافة وظيفة التصدير لاحقاً
        return redirect()->back()->with('info', 'وظيفة التصدير قيد التطوير');
    }
}
