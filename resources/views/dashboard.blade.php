{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - مدرسة زاد الغد</title>
    {{-- في Laravel استبدل بـ @vite(['resources/css/app.css']) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        :root {
            --navy: #1a237e;
            --navy-dark: #0d1257;
            --navy-mid: #283593;
            --navy-light: #3949ab;
            --navy-lighter: #5c6bc0;
            --gold: #ffd600;
            --gold-soft: #fff9c4;
            --sidebar-w: 260px;
            --bg: #f0f2fa;
            --card-bg: #ffffff;
            --text-main: #1a237e;
            --text-muted: #78909c;
            --border: #e8eaf6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy-dark);
            min-height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-logo {
            padding: 28px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo img {
            height: 45px;
            filter: brightness(0) invert(1);
        }

        .sidebar-logo-text {
            color: white;
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.3;
        }

        .sidebar-logo-sub {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.4);
            font-weight: 400;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
        }

        .nav-section-title {
            padding: 10px 20px 6px;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 20px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.25s ease;
            position: relative;
            margin: 2px 10px;
            border-radius: 10px;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.07);
            color: white;
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(255,214,0,0.15), rgba(255,214,0,0.05));
            color: var(--gold);
            border: 1px solid rgba(255,214,0,0.2);
        }

        .nav-item.active .nav-icon {
            color: var(--gold);
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
            padding: 8px 0;
        }

        .logout-btn:hover { color: #ef5350; }

        /* ─── MAIN CONTENT ─── */
        .main-content {
            margin-right: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* ─── TOPBAR ─── */
        .topbar {
            background: white;
            padding: 16px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--navy-dark);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-date {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg);
            padding: 8px 14px;
            border-radius: 50px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--navy), var(--navy-lighter));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.85rem;
        }

        .user-name {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--navy-dark);
        }

        /* ─── PAGE BODY ─── */
        .page-body {
            padding: 30px;
            flex: 1;
        }

        /* ─── STATS CARDS ─── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: 0 2px 12px rgba(26,35,126,0.05);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            animation: slideUp 0.5s ease both;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(26,35,126,0.12);
        }

        .stat-icon-wrap {
            width: 62px;
            height: 62px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .icon-blue { background: linear-gradient(135deg, #1a237e, #3949ab); color: white; }
        .icon-rose { background: linear-gradient(135deg, #880e4f, #e91e8c); color: white; }
        .icon-gold { background: linear-gradient(135deg, #e65100, #ffd600); color: white; }
        .icon-green { background: linear-gradient(135deg, #1b5e20, #43a047); color: white; }
        .icon-purple { background: linear-gradient(135deg, #4a148c, #7b1fa2); color: white; }
        .icon-red { background: linear-gradient(135deg, #b71c1c, #e53935); color: white; }

        .stat-info { flex: 1; }

        .stat-num {
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--navy-dark);
            line-height: 1;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.88rem;
            margin-top: 6px;
            font-weight: 500;
        }

        /* ─── CHARTS ROW ─── */
        .charts-row {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }

        .chart-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(26,35,126,0.05);
            border: 1px solid var(--border);
            animation: slideUp 0.5s ease 0.4s both;
        }

        .card-header-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border);
        }

        .card-title-custom {
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title-custom i {
            color: var(--navy-lighter);
            font-size: 0.9rem;
        }

        .card-subtitle {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* ─── SUMMARY TABLE ─── */
        .table-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(26,35,126,0.05);
            border: 1px solid var(--border);
            animation: slideUp 0.5s ease 0.5s both;
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table thead th {
            background: var(--bg);
            color: var(--navy-dark);
            font-weight: 700;
            padding: 13px 16px;
            font-size: 0.88rem;
            text-align: right;
            border: none;
        }

        .custom-table thead th:first-child { border-radius: 10px 0 0 10px; }
        .custom-table thead th:last-child { border-radius: 0 10px 10px 0; }

        .custom-table tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
            color: #37474f;
            text-align: right;
            vertical-align: middle;
        }

        .custom-table tbody tr:last-child td { border-bottom: none; }
        .custom-table tbody tr:hover td { background: #f8f9ff; }

        .grade-chip {
            display: inline-block;
            padding: 5px 14px;
            background: linear-gradient(135deg, var(--navy), var(--navy-lighter));
            color: white;
            border-radius: 20px;
            font-size: 0.83rem;
            font-weight: 600;
        }

        .progress-wrap {
            width: 100%;
            height: 7px;
            background: var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--navy), var(--navy-lighter));
            border-radius: 10px;
            transition: width 0.8s ease;
        }

        /* ─── QUICK ACTIONS ─── */
        .quick-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 12px;
            font-family: 'Tajawal', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .action-btn-primary {
            background: linear-gradient(135deg, var(--navy-dark), var(--navy-light));
            color: white;
        }

        .action-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26,35,126,0.3);
            color: white;
        }

        .action-btn-outline {
            background: white;
            color: var(--navy);
            border: 2px solid var(--border);
        }

        .action-btn-outline:hover {
            border-color: var(--navy-lighter);
            color: var(--navy);
            transform: translateY(-2px);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 1024px) {
            .charts-row { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(100%); }
            .main-content { margin-right: 0; }
            .stats-grid { grid-template-columns: 1fr; }
            .page-body { padding: 16px; }
        }
    </style>
</head>
<body>

<!-- ─── SIDEBAR ─── -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo-white.png') }}" alt="زاد الغد"
             onerror="this.style.display='none'">
        <div>
            <div class="sidebar-logo-text">زاد الغد</div>
            <div class="sidebar-logo-sub">نظام الإدارة المدرسية</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">الرئيسية</div>
        <a href="{{ route('dashboard') }}" class="nav-item active">
            <i class="fas fa-chart-pie nav-icon"></i>
            <span>لوحة التحكم</span>
        </a>

        <div class="nav-section-title" style="margin-top:10px;">إدارة الطلاب</div>
        <a href="{{ route('students.index') }}" class="nav-item">
            <i class="fas fa-users nav-icon"></i>
            <span>قائمة الطلاب</span>
        </a>
        <a href="{{ route('students.register') }}" class="nav-item">
            <i class="fas fa-user-plus nav-icon"></i>
            <span>إضافة طالب</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn" style="background:none; border:none; width:100%; cursor:pointer;">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
</aside>

<!-- ─── MAIN CONTENT ─── -->
<div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-title">لوحة التحكم</div>
        <div class="topbar-actions">
            <span class="topbar-date">
                <i class="far fa-calendar-alt me-1"></i>
                {{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
            </span>
            <div class="user-badge">
                <div class="user-avatar"><i class="fas fa-user"></i></div>
                <span class="user-name">{{ auth()->user()->name ?? 'المدير' }}</span>
            </div>
        </div>
    </div>

    <div class="page-body">

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('students.register') }}" class="action-btn action-btn-primary">
                <i class="fas fa-user-plus"></i> إضافة طالب جديد
            </a>
            <a href="{{ route('students.index') }}" class="action-btn action-btn-outline">
                <i class="fas fa-list"></i> عرض جميع الطلاب
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon-wrap icon-blue">
                    <i class="fas fa-male"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-num counter" data-target="{{ $maleStudents }}">{{ $maleStudents }}</div>
                    <div class="stat-label">عدد الطلاب الذكور</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrap icon-rose">
                    <i class="fas fa-female"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-num counter" data-target="{{ $femaleStudents }}">{{ $femaleStudents }}</div>
                    <div class="stat-label">عدد الطالبات الإناث</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrap icon-gold">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-num counter" data-target="{{ $totalStudents }}">{{ $totalStudents }}</div>
                    <div class="stat-label">إجمالي الطلاب</div>
                </div>
            </div>
        </div>

        <!-- Financial Stats Cards -->
        <div class="stats-grid" style="margin-top:20px;">
            <div class="stat-card">
                <div class="stat-icon-wrap icon-blue">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-num">{{ number_format($totalExpectedRevenue, 2) }} ₪</div>
                    <div class="stat-label">إجمالي الرسوم المستحقة (بعد الخصومات)</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrap icon-green">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-num">{{ number_format($totalCollected, 2) }} ₪</div>
                    <div class="stat-label">إجمالي المحصّل فعلياً</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrap icon-red">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-num">{{ number_format($totalOutstanding, 2) }} ₪</div>
                    <div class="stat-label">إجمالي المتبقي ({{ $registrationsWithBalanceCount }} تسجيل)</div>
                </div>
            </div>
        </div>

        @if($topOutstanding->isNotEmpty())
        <div class="chart-card" style="margin-top:20px; background:white; border-radius:16px; border:1px solid #e8eaf6; padding:24px; box-shadow: 0 2px 12px rgba(26,35,126,0.05);">
            <h5 style="color:#1a237e; font-weight:700; margin-bottom:16px;">
                <i class="fas fa-exclamation-triangle" style="color:#e53935;"></i> أعلى 5 تسجيلات بمتبقي مالي
            </h5>
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8f9ff;">
                        <th style="padding:10px 14px; text-align:right; font-size:0.8rem; color:#1a237e;">الطالب</th>
                        <th style="padding:10px 14px; text-align:right; font-size:0.8rem; color:#1a237e;">الشعبة</th>
                        <th style="padding:10px 14px; text-align:right; font-size:0.8rem; color:#1a237e;">المتبقي</th>
                        <th style="padding:10px 14px; text-align:right; font-size:0.8rem; color:#1a237e;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topOutstanding as $reg)
                    <tr>
                        <td style="padding:10px 14px; font-size:0.85rem; border-bottom:1px solid #f0f2fa;">{{ $reg->student->full_name ?? '—' }}</td>
                        <td style="padding:10px 14px; font-size:0.85rem; border-bottom:1px solid #f0f2fa;">{{ $reg->schoolClass->name ?? '—' }}</td>
                        <td style="padding:10px 14px; font-size:0.85rem; border-bottom:1px solid #f0f2fa; color:#c62828; font-weight:700;">{{ number_format($reg->computed_outstanding, 2) }} ₪</td>
                        <td style="padding:10px 14px; border-bottom:1px solid #f0f2fa;">
                            <a href="{{ route('payments.index', $reg->id) }}" style="color:#3949ab; font-size:0.8rem; font-weight:700; text-decoration:none;">
                                عرض الدفعات <i class="fas fa-arrow-left"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Charts Row -->
        <div class="charts-row">
            <!-- Bar Chart -->
            <div class="chart-card">
                <div class="card-header-custom">
                    <div>
                        <div class="card-title-custom">
                            <i class="fas fa-chart-bar"></i>
                            توزيع الطلاب حسب الصف
                        </div>
                        <div class="card-subtitle">توزيع تفصيلي للطلاب على الصفوف الدراسية</div>
                    </div>
                </div>
                <canvas id="gradeChart" height="200"></canvas>
            </div>

            <!-- Line Chart -->
            <div class="chart-card">
                <div class="card-header-custom">
                    <div>
                        <div class="card-title-custom">
                            <i class="fas fa-chart-line"></i>
                            التسجيلات الشهرية
                        </div>
                        <div class="card-subtitle">آخر 6 أشهر</div>
                    </div>
                </div>
                <canvas id="monthlyChart" height="200"></canvas>
            </div>
        </div>

        <!-- Summary Table -->
        <div class="table-card">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="fas fa-table"></i>
                    ملخص التوزيع حسب الصف
                </div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
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
                            <td><span class="grade-chip">{{ $grade->gradeByAge }}</span></td>
                            <td><strong>{{ $grade->count }}</strong></td>
                            <td>{{ $totalStudents > 0 ? number_format(($grade->count / $totalStudents * 100), 1) : 0 }}%</td>
                            <td style="min-width:120px;">
                                <div class="progress-wrap">
                                    <div class="progress-fill" style="width:{{ $totalStudents > 0 ? ($grade->count / $totalStudents * 100) : 0 }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;color:#9e9e9e;padding:30px;">لا توجد بيانات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
// Charts
const gradeCtx = document.getElementById('gradeChart').getContext('2d');
new Chart(gradeCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($gradeDistribution->pluck('gradeByAge')) !!},
        datasets: [{
            data: {!! json_encode($gradeDistribution->pluck('count')) !!},
            backgroundColor: 'rgba(26, 35, 126, 0.75)',
            borderRadius: 8,
            barThickness: 36,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { rtl: true, backgroundColor: '#0d1257' } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { family: 'Tajawal', size: 12 }, color: '#78909c' } },
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }, ticks: { stepSize: 1, font: { family: 'Tajawal' }, color: '#78909c' } }
        }
    }
});

const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyRegistrations->pluck('month_name')) !!},
        datasets: [{
            data: {!! json_encode($monthlyRegistrations->pluck('count')) !!},
            backgroundColor: 'rgba(89, 107, 192, 0.12)',
            borderColor: '#3949ab',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 6,
            pointBackgroundColor: '#1a237e',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { rtl: true, backgroundColor: '#0d1257' } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { family: 'Tajawal', size: 11 }, color: '#78909c', maxRotation: 45 } },
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }, ticks: { stepSize: 1, font: { family: 'Tajawal' }, color: '#78909c' } }
        }
    }
});

// Counter animation
document.querySelectorAll('.counter').forEach(el => {
    const target = parseInt(el.dataset.target);
    let cur = 0;
    const step = Math.ceil(target / 40);
    const timer = setInterval(() => {
        cur = Math.min(cur + step, target);
        el.textContent = cur;
        if (cur >= target) clearInterval(timer);
    }, 25);
});
</script>
</body>
</html>
