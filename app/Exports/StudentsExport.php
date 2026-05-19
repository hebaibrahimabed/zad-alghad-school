<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
     protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
  return $this->students->map(function ($student) {
    $healthStatuses = [
    'Healthy' => 'سليم',
    'disabled' => 'ذوي إعاقة',
    'injured' => 'مصاب',
];

$orphanStatuses = [
    'orphan' => 'يتيم',
    'not orphan' => 'غير يتيم',
];
            return [
                'اسم الطالب' => $student->full_name,
                'رقم الهوية' => $student->IDNumber,
                'الصف' => $student->gradeByAge,
                'رقم الهاتف' => $student->Parentmobile,


    'الحالة الصحية' => $healthStatuses[$student->healthCondition] ?? 'غير محدد',
    'حالة اليتيم' => $orphanStatuses[$student->OrphanStatus] ?? 'غير محدد',

            ];
        });
    }

public function headings(): array
    {
        return [
            'اسم الطالب',
            'رقم الهوية',
            'الصف',
            'رقم الهاتف',
            'الحالة الصحية',
            'حالة اليتيم'
        ];
    }
}
