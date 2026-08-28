<?php

namespace App\Http\Controllers\students;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Http\Request;
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
     * ملاحظة: create()/store() تم حذفهما من هنا — إنشاء طالب جديد صار
     * حصراً عبر معالج التسجيل الشامل (راجع StudentRegistrationController@create/store)
     * الذي يربط الطالب بولي أمر وتسجيل بشعبة وخصومات في عملية واحدة.
     */

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
        $parents = ParentModel::orderBy('first_name')->get();
        return view('students.edit', compact('student', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, string $id)
    {
        $student = Student::findOrFail($id);
        $data = $request->validated();
        $data['gradeByAge'] = Student::determineGradeByAge($data['dateOfBirth']);

        try {
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
