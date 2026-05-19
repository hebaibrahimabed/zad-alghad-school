{{-- resources/views/layouts/zad.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'مدرسة زاد الغد') - زاد الغد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
    <style>
        :root {
            --navy: #1a237e;
            --navy-dark: #0d1257;
            --navy-mid: #283593;
            --navy-light: #3949ab;
            --navy-lighter: #5c6bc0;
            --gold: #ffd600;
            --sidebar-w: 260px;
            --bg: #f0f2fa;
            --border: #e8eaf6;
            --text-muted: #78909c;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy-dark);
            min-height: 100vh;
            position: fixed;
            right: 0; top: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            box-shadow: -4px 0 20px rgba(0,0,0,0.15);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 20% 10%, rgba(57,73,171,0.3) 0%, transparent 60%);
            pointer-events: none;
        }

        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .sidebar-logo img {
            height: 42px;
            filter: brightness(0) invert(1);
            object-fit: contain;
        }

        .logo-text-wrap { flex: 1; }
        .logo-name { color: white; font-size: 1.05rem; font-weight: 800; }
        .logo-sub { color: rgba(255,255,255,0.35); font-size: 0.68rem; font-weight: 400; }

        .sidebar-nav { flex: 1; padding: 16px 0; overflow-y: auto; position: relative; z-index: 1; }

        .nav-section {
            padding: 8px 16px 4px;
            font-size: 0.65rem;
            color: rgba(255,255,255,0.28);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            margin-top: 8px;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 16px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            margin: 1px 10px;
            border-radius: 10px;
            transition: all 0.22s ease;
        }

        .nav-link-item:hover {
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.9);
        }

        .nav-link-item.active {
            background: rgba(255,214,0,0.12);
            color: var(--gold);
            border: 1px solid rgba(255,214,0,0.18);
        }

        .nav-link-item .ni {
            width: 18px;
            text-align: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.07);
            position: relative;
            z-index: 1;
        }

        .user-info-small {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            margin-bottom: 10px;
        }

        .user-av {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--navy-light), var(--navy-lighter));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 0.75rem; flex-shrink: 0;
        }

        .user-n { color: white; font-size: 0.82rem; font-weight: 600; }
        .user-r { color: rgba(255,255,255,0.35); font-size: 0.7rem; }

        .logout-link {
            display: flex; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.4);
            font-size: 0.85rem; cursor: pointer;
            background: none; border: none; width: 100%;
            padding: 8px 10px; border-radius: 8px;
            font-family: 'Tajawal', sans-serif;
            transition: all 0.2s;
        }
        .logout-link:hover { color: #ef5350; background: rgba(239,83,80,0.08); }

        /* MAIN */
        .main-wrapper {
            margin-right: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .topbar {
            background: white;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 50;
        }

        .breadcrumb-bar { display: flex; align-items: center; gap: 8px; }
        .breadcrumb-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--navy-dark);
        }
        .breadcrumb-sep { color: #ccc; }
        .breadcrumb-sub { font-size: 0.88rem; color: var(--text-muted); }

        .topbar-right { display: flex; align-items: center; gap: 14px; }

        .topbar-date {
            font-size: 0.82rem;
            color: var(--text-muted);
            background: var(--bg);
            padding: 7px 14px;
            border-radius: 20px;
        }

        .page-content { padding: 28px; flex: 1; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(26,35,126,0.2); border-radius: 3px; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(100%); }
            .main-wrapper { margin-right: 0; }
            .page-content { padding: 16px; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/ZadAlghad_Logo_W_2000x.jpg') }}" alt="زاد الغد"
             onerror="this.style.display='none'"
             >
        <div class="logo-text-wrap">
            <div class="logo-name">زاد الغد</div>
            <div class="logo-sub">نظام الإدارة المدرسية</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">الرئيسية</div>
        <a href="{{ route('dashboard') }}"
           class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie ni"></i>
            <span>لوحة التحكم</span>
        </a>

        <div class="nav-section">الطلاب</div>
        <a href="{{ route('students.index') }}"
           class="nav-link-item {{ request()->routeIs('students.index') ? 'active' : '' }}">
            <i class="fas fa-users ni"></i>
            <span>قائمة الطلاب</span>
        </a>
        <a href="{{ route('students.create') }}"
           class="nav-link-item {{ request()->routeIs('students.create') ? 'active' : '' }}">
            <i class="fas fa-user-plus ni"></i>
            <span>إضافة طالب جديد</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info-small">
            <div class="user-av"><i class="fas fa-user"></i></div>
            <div>
                <div class="user-n">{{ auth()->user()->name ?? 'المدير' }}</div>
                <div class="user-r">مدير النظام</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
</aside>

<!-- MAIN WRAPPER -->
<div class="main-wrapper">
    <div class="topbar">
        <div class="breadcrumb-bar">
            <span class="breadcrumb-title">@yield('page-title', 'لوحة التحكم')</span>
        </div>
        <div class="topbar-right">
            <div class="topbar-date">
                <i class="far fa-calendar-alt me-1"></i>
                {{ now()->locale('ar')->isoFormat('D MMMM YYYY') }}
            </div>
        </div>
    </div>

    <div class="page-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
