<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics
     */
    public function index()
    {
        // عدد الطلاب الذكور
        $maleStudents = Student::query()->where('gender', 'male')->count();

        // عدد الطالبات الإناث
        $femaleStudents = Student::query()->where('gender', 'female')->count();

        // إجمالي عدد الطلاب
        $totalStudents = $maleStudents + $femaleStudents;

        // التوزيع حسب الصف الدراسي
        $gradeDistribution = Student::query()->select('gradeByAge', DB::raw('count(*) as count'))
            ->whereNotNull('gradeByAge')
            ->where('gradeByAge', '!=', '')
            ->groupBy('gradeByAge')
            ->orderBy('gradeByAge')
            ->get();

        // إحصائيات التسجيل الشهرية (آخر 6 أشهر)
        $monthlyRegistrations = Student::query()->select(
                DB::raw('DATE_FORMAT(registrationDate, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->where('registrationDate', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // تحويل أسماء الأشهر للعربية
        $monthNames = [
            '01' => 'يناير',
            '02' => 'فبراير',
            '03' => 'مارس',
            '04' => 'أبريل',
            '05' => 'مايو',
            '06' => 'يونيو',
            '07' => 'يوليو',
            '08' => 'أغسطس',
            '09' => 'سبتمبر',
            '10' => 'أكتوبر',
            '11' => 'نوفمبر',
            '12' => 'ديسمبر',
        ];

        $monthlyRegistrations = $monthlyRegistrations->map(function($item) use ($monthNames) {
            $parts = explode('-', $item->month);
            $year = $parts[0];
            $month = $parts[1];
            $item->month_name = $monthNames[$month] . ' ' . $year;
            return $item;
        });

        return view('dashboard', compact(
            'totalStudents',
            'maleStudents',
            'femaleStudents',
            'gradeDistribution',
            'monthlyRegistrations'
        ));
    }
}
