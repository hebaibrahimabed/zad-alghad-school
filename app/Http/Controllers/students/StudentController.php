<?php

namespace App\Http\Controllers\students;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = $this->filterStudents($request)
            ->orderBy('registrationDate', 'desc')
            ->paginate(30);

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
            $data['gradeByAge'] = Student::determineGradeByAge(
                $request->dateOfBirth
            );
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
            $data['gradeByAge'] = Student::determineGradeByAge(
                $request->dateOfBirth
            );

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
    public function exportExcel(Request $request)
    {
        $students = $this->filterStudents($request)
            ->orderBy('registrationDate', 'desc')
            ->get();

        return Excel::download(
            new StudentsExport($students),
            'students.xlsx'
        );
    }



    private function filterStudents(Request $request)
    {
        $query = Student::query();

        // البحث باسم الطالب
        if ($request->filled('studentName')) {
            $query->where('studentName', 'like', '%' . $request->studentName . '%');
        }

        // البحث باسم الأب
        if ($request->filled('FatherName')) {
            $query->where('FatherName', 'like', '%' . $request->FatherName . '%');
        }

        // البحث باسم الجد
        if ($request->filled('GrandfatherName')) {
            $query->where('GrandfatherName', 'like', '%' . $request->GrandfatherName . '%');
        }

        // البحث باسم العائلة
        if ($request->filled('lastName')) {
            $query->where('lastName', 'like', '%' . $request->lastName . '%');
        }

        // البحث برقم الهوية
        if ($request->filled('IDNumber')) {
            $query->where('IDNumber', 'like', '%' . $request->IDNumber . '%');
        }

        // البحث برقم الهاتف
        if ($request->filled('Parentmobile')) {
            $query->where('Parentmobile', 'like', '%' . $request->Parentmobile . '%');
        }

        // البحث بالصف
        if ($request->filled('gradeByAge')) {
            $query->where('gradeByAge', 'like', '%' . $request->gradeByAge . '%');
        }

        // البحث بآخر شهادة
        if ($request->filled('lastCertificate')) {
            $query->where('lastCertificateObtained', 'like', '%' . $request->lastCertificate . '%');
        }

        // الجنس
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // الحالة الصحية
        if ($request->filled('healthCondition')) {
            $query->where('healthCondition', $request->healthCondition);
        }

        // ملاحظة: تم نقل حالة اليتم إلى جدول parents، وحالة الدفع إلى payments،
        // وحالة تسجيل الوزارة إلى registrations. سيتم ربط الفلترة بها لاحقاً
        // عبر علاقات Eloquent بعد بناء واجهات هذه الجداول.

        // نطاق تاريخ التسجيل
        if ($request->filled('dateFrom')) {
            $query->whereDate('registrationDate', '>=', $request->dateFrom);
        }

        if ($request->filled('dateTo')) {
            $query->whereDate('registrationDate', '<=', $request->dateTo);
        }

        return $query;
    }
}
