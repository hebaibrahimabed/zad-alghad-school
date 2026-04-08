@extends('adminlte::page')

@section('title', 'لوحة التحكم')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 dashboard-title">لوحة التحكم</h1>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        {{-- Male Students --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card card-blue">
                <div class="stats-icon">
                    <i class="fas fa-male"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $maleStudents }}</h3>
                    <p class="stats-label">عدد الطلاب الذكور</p>
                </div>
            </div>
        </div>

        {{-- Female Students --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card card-purple">
                <div class="stats-icon">
                    <i class="fas fa-female"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $femaleStudents }}</h3>
                    <p class="stats-label">عدد الطالبات الإناث</p>
                </div>
            </div>
        </div>

        {{-- Total Students --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card card-teal">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $totalStudents }}</h3>
                    <p class="stats-label">إجمالي الطلاب</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row">
        {{-- Grade Distribution Chart --}}
        <div class="col-lg-7 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        توزيع الطلاب حسب الصف الدراسي
                    </h5>
                </div>
                <div class="chart-body">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Registrations Chart --}}
        <div class="col-lg-5 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        التسجيلات الشهرية
                    </h5>
                    <p class="chart-subtitle">آخر 6 أشهر</p>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Table --}}
    <div class="row">
        <div class="col-12">
            <div class="summary-card">
                <div class="summary-header">
                    <h5 class="summary-title">
                        <i class="fas fa-table"></i>
                        ملخص التوزيع حسب الصف
                    </h5>
                </div>
                <div class="summary-body">
                    <div class="table-responsive">
                        <table class="summary-table">
                            <thead>
                                <tr>
                                    <th>الصف الدراسي</th>
                                    <th>عدد الطلاب</th>
                                    <th>النسبة المئوية</th>
                                    <th>التمثيل البصري</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gradeDistribution as $grade)
                                    <tr>
                                        <td>
                                            <span class="grade-badge">{{ $grade->gradeByAge }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $grade->count }}</strong>
                                        </td>
                                        <td>
                                            {{ $totalStudents > 0 ? number_format(($grade->count / $totalStudents * 100), 1) : 0 }}%
                                        </td>
                                        <td>
                                            <div class="progress-bar-container">
                                                <div class="progress-bar-fill"
                                                     style="width: {{ $totalStudents > 0 ? ($grade->count / $totalStudents * 100) : 0 }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            لا توجد بيانات
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
    /* General Styles */
    body {
        background-color: #f4f6f9;
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.8rem;
    }

    /* Statistics Cards */
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        border: none;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .stats-icon {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 20px;
        font-size: 2rem;
    }

    .card-blue .stats-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .card-purple .stats-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .card-teal .stats-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stats-content {
        flex: 1;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        line-height: 1;
    }

    .stats-label {
        color: #7f8c8d;
        font-size: 0.95rem;
        margin: 8px 0 0 0;
        font-weight: 500;
    }

    /* Chart Cards */
    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        height: 100%;
    }

    .chart-header {
        margin-bottom: 20px;
        border-bottom: 2px solid #ecf0f1;
        padding-bottom: 15px;
    }

    .chart-title {
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    .chart-title i {
        color: #667eea;
        margin-left: 8px;
    }

    .chart-subtitle {
        color: #95a5a6;
        font-size: 0.85rem;
        margin: 5px 0 0 0;
    }

    .chart-body {
        padding: 10px 0;
    }

    /* Summary Card */
    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }

    .summary-header {
        margin-bottom: 20px;
        border-bottom: 2px solid #ecf0f1;
        padding-bottom: 15px;
    }

    .summary-title {
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    .summary-title i {
        color: #667eea;
        margin-left: 8px;
    }

    /* Table Styles */
    .summary-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .summary-table thead th {
        background: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
        padding: 15px;
        text-align: right;
        border: none;
        font-size: 0.95rem;
    }

    .summary-table thead th:first-child {
        border-radius: 8px 0 0 0;
    }

    .summary-table thead th:last-child {
        border-radius: 0 8px 0 0;
    }

    .summary-table tbody td {
        padding: 15px;
        border-bottom: 1px solid #ecf0f1;
        color: #34495e;
        text-align: right;
    }

    .summary-table tbody tr:last-child td {
        border-bottom: none;
    }

    .summary-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .grade-badge {
        display: inline-block;
        padding: 6px 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background: #ecf0f1;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 15px;
        }

        .stats-number {
            font-size: 2rem;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // إعدادات الألوان الهادئة
    const colors = {
        primary: 'rgba(102, 126, 234, 0.8)',
        primaryLight: 'rgba(102, 126, 234, 0.2)',
        secondary: 'rgba(118, 75, 162, 0.8)',
        secondaryLight: 'rgba(118, 75, 162, 0.2)',
        accent: 'rgba(79, 172, 254, 0.8)',
        accentLight: 'rgba(79, 172, 254, 0.2)',
        success: 'rgba(72, 187, 120, 0.8)',
        successLight: 'rgba(72, 187, 120, 0.2)',
    };

    // Grade Distribution Chart - Bar Chart
    const gradeCtx = document.getElementById('gradeChart').getContext('2d');
    const gradeLabels = {!! json_encode($gradeDistribution->pluck('gradeByAge')) !!};
    const gradeData = {!! json_encode($gradeDistribution->pluck('count')) !!};

    const gradeChart = new Chart(gradeCtx, {
        type: 'bar',
        data: {
            labels: gradeLabels,
            datasets: [{
                label: 'عدد الطلاب',
                data: gradeData,
                backgroundColor: colors.primary,
                borderColor: colors.primary,
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(44, 62, 80, 0.9)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        color: '#7f8c8d'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        },
                        color: '#7f8c8d'
                    }
                }
            }
        }
    });

    // Monthly Registrations Chart - Line Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyLabels = {!! json_encode($monthlyRegistrations->pluck('month_name')) !!};
    const monthlyData = {!! json_encode($monthlyRegistrations->pluck('count')) !!};

    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'عدد التسجيلات',
                data: monthlyData,
                backgroundColor: colors.accentLight,
                borderColor: colors.accent,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: colors.accent,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: colors.accent,
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(44, 62, 80, 0.9)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        color: '#7f8c8d',
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        },
                        color: '#7f8c8d'
                    }
                }
            }
        }
    });

    // تحريك الأرقام عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        const numbers = document.querySelectorAll('.stats-number');
        numbers.forEach(num => {
            const target = parseInt(num.innerText);
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    num.innerText = target;
                    clearInterval(timer);
                } else {
                    num.innerText = Math.floor(current);
                }
            }, 20);
        });
    });
</script>
@stop
