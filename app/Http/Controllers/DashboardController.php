<?php

namespace App\Http\Controllers;

use App\Models\Registration;
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
        // ملاحظة: كان هذا الاستعلام يستخدم DATE_FORMAT() وهي دالة خاصة بـ MySQL فقط
        // وتفشل على قواعد بيانات أخرى (SQLite/PostgreSQL). استبدلناها بتجميع
        // متوافق مع أي قاعدة بيانات عبر Carbon بدل الاعتماد على دالة SQL معيّنة.
        $monthlyRegistrations = Student::query()
            ->where('registrationDate', '>=', now()->subMonths(6))
            ->get(['registrationDate'])
            ->groupBy(fn ($student) => $student->registrationDate->format('Y-m'))
            ->map(function ($group, $month) {
                return (object) ['month' => $month, 'count' => $group->count()];
            })
            ->sortBy('month')
            ->values();

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

        // ============================================
        // الإحصائيات المالية (Payments/Discounts)
        // ملاحظة: نحسب الرصيد على مستوى كل تسجيل نشط بدل الاعتماد على
        // مجموع عمود total_outstanding بجدول payments مباشرة، لأن أي تسجيل
        // بدون أي دفعة مسجّلة أصلاً (صفر صفوف) لازم يظهر برصيد = كامل الرسوم،
        // وهذا لا يظهر لو جمعنا فقط أعمدة الصفوف الموجودة فعلياً.
        $activeRegistrations = Registration::with(['schoolClass', 'discounts', 'payments', 'student'])
            ->where('current_status', 'active')
            ->get();

        $totalExpectedRevenue = 0;
        $totalCollected = 0;
        $totalOutstanding = 0;
        $registrationsWithBalance = collect();

        foreach ($activeRegistrations as $reg) {
            $fee = $reg->schoolClass->price ?? 0;
            $discountsSum = $reg->discounts->sum('applied_value');
            $netFee = max(0, $fee - $discountsSum);
            $paid = $reg->payments->sum('amount_paid');
            $outstanding = max(0, $netFee - $paid);

            $totalExpectedRevenue += $netFee;
            $totalCollected += $paid;
            $totalOutstanding += $outstanding;

            if ($outstanding > 0) {
                $reg->computed_outstanding = $outstanding;
                $registrationsWithBalance->push($reg);
            }
        }

        $topOutstanding = $registrationsWithBalance
            ->sortByDesc('computed_outstanding')
            ->take(5);

        $registrationsWithBalanceCount = $registrationsWithBalance->count();

        return view('dashboard', compact(
            'totalStudents',
            'maleStudents',
            'femaleStudents',
            'gradeDistribution',
            'monthlyRegistrations',
            'totalExpectedRevenue',
            'totalCollected',
            'totalOutstanding',
            'registrationsWithBalanceCount',
            'topOutstanding'
        ));
    }
}
