<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدرسة زاد الغد - تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --navy: #1a237e;
            --navy-dark: #0d1257;
            --navy-mid: #283593;
            --navy-light: #3949ab;
            --gold: #ffd600;
            --gold-soft: #fff9c4;
            --white: #ffffff;
            --gray-soft: #f0f2f8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* Left Panel - Decorative */
        .side-panel {
            width: 55%;
            background: var(--navy-dark);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .side-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(57, 73, 171, 0.5) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(255, 214, 0, 0.08) 0%, transparent 50%);
        }

        /* Geometric pattern background */
        .geo-bg {
            position: absolute;
            inset: 0;
            opacity: 0.06;
            background-image:
                linear-gradient(30deg, var(--white) 12%, transparent 12.5%, transparent 87%, var(--white) 87.5%),
                linear-gradient(150deg, var(--white) 12%, transparent 12.5%, transparent 87%, var(--white) 87.5%),
                linear-gradient(30deg, var(--white) 12%, transparent 12.5%, transparent 87%, var(--white) 87.5%),
                linear-gradient(150deg, var(--white) 12%, transparent 12.5%, transparent 87%, var(--white) 87.5%);
            background-size: 80px 140px;
            background-position: 0 0, 0 0, 40px 70px, 40px 70px;
        }

        /* Floating circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.1);
            animation: float 8s ease-in-out infinite;
        }
        .circle-1 { width: 300px; height: 300px; top: -80px; right: -80px; animation-delay: 0s; }
        .circle-2 { width: 200px; height: 200px; bottom: 10%; left: 10%; animation-delay: 2s; }
        .circle-3 { width: 150px; height: 150px; top: 40%; right: 5%; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .side-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px;
        }

        .logo-wrapper {
            margin-bottom: 40px;
            animation: fadeInDown 0.8s ease;
        }

        .logo-img {
            width: 200px;
            filter: brightness(0) invert(1);
            drop-shadow(0 10px 30px rgba(0,0,0,0.3));
        }

        .side-tagline {
            color: rgba(255,255,255,0.9);
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.8;
            animation: fadeInUp 0.8s ease 0.3s both;
        }

        .side-tagline span {
            color: var(--gold);
        }

        .side-desc {
            color: rgba(255,255,255,0.5);
            font-size: 1rem;
            margin-top: 15px;
            font-weight: 300;
            animation: fadeInUp 0.8s ease 0.5s both;
        }

        .stats-row {
            display: flex;
            gap: 30px;
            margin-top: 50px;
            animation: fadeInUp 0.8s ease 0.7s both;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-num {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gold);
        }

        .stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            margin-top: 4px;
        }

        /* Right Panel - Login Form */
        .login-panel {
            width: 45%;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .login-panel::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0; /* RTL: right side is the visual left */
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--navy-dark), var(--gold), var(--navy-dark));
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            animation: fadeInRight 0.8s ease;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--navy-dark);
        }

        .login-header p {
            color: #9e9e9e;
            font-size: 0.95rem;
            margin-top: 8px;
        }

        .login-header .accent-line {
            width: 50px;
            height: 4px;
            background: linear-gradient(90deg, var(--navy), var(--gold));
            border-radius: 2px;
            margin-top: 12px;
        }

        .form-label {
            font-weight: 600;
            color: var(--navy-dark);
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #bdbdbd;
            z-index: 5;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .form-control {
            border: 2px solid #e8eaf6;
            border-radius: 12px;
            padding: 14px 45px 14px 16px;
            font-family: 'Tajawal', sans-serif;
            font-size: 0.95rem;
            color: var(--navy-dark);
            background: var(--gray-soft);
            transition: all 0.3s ease;
            direction: rtl;
        }

        .form-control:focus {
            border-color: var(--navy-light);
            background: white;
            box-shadow: 0 0 0 4px rgba(57, 73, 171, 0.08);
            outline: none;
        }

        .form-control:focus + .input-icon,
        .input-group:focus-within .input-icon {
            color: var(--navy-light);
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 15px 0 25px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #c5cae9;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--navy);
            border-color: var(--navy);
        }

        .form-check-label {
            font-size: 0.88rem;
            color: #616161;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-light) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: 'Tajawal', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 35, 126, 0.35);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 14px 18px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #fce4ec;
            color: #c62828;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e8eaf6, transparent);
            margin: 30px 0;
        }

        .footer-text {
            text-align: center;
            color: #bdbdbd;
            font-size: 0.8rem;
            margin-top: 30px;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .side-panel { width: 100%; padding: 40px 20px; min-height: 250px; }
            .login-panel { width: 100%; }
            .stats-row { display: none; }
            .side-tagline { font-size: 1.2rem; }
            .login-panel::before { display: none; }
        }
    </style>
</head>
<body>

<!-- Side Panel -->
<div class="side-panel">
    <div class="geo-bg"></div>
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>

    <div class="side-content">
        <div class="logo-wrapper">
            {{-- استبدل بمسار الشعار الحقيقي --}}

            <img src="{{ asset('images/ZadAlghad_Logo_W_2000x.jpg') }}" alt="زاد الغد" class="logo-img"
                 onerror="this.style.display='none'; document.getElementById('text-logo').style.display='block'">
            <div id="text-logo" style="display:none; color:white; font-size:3rem; font-weight:900;">زاد الغد</div>
        </div>

        <div class="side-tagline">
            نظام إدارة مدرسة<br>
            <span>زاد الغد</span>
        </div>

        <div class="side-desc">
            منصة متكاملة لإدارة شؤون الطلاب والتسجيل والإحصائيات
        </div>

        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-num">∞</div>
                <div class="stat-label">طالب مسجل</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">100%</div>
                <div class="stat-label">دقة البيانات</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">24/7</div>
                <div class="stat-label">متاح دائماً</div>
            </div>
        </div>
    </div>
</div>

<!-- Login Panel -->
<div class="login-panel">
    <div class="login-box">
        <div class="login-header">
            <h2>مرحباً بعودتك</h2>
            <p>سجّل دخولك للوصول إلى لوحة التحكم</p>
            <div class="accent-line"></div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">البريد الإلكتروني</label>
                <div class="input-group">
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="example@email.com"
                           required autofocus>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">كلمة المرور</label>
                <div class="input-group">
                    <input type="password"
                           name="password"
                           id="passwordInput"
                           class="form-control"
                           placeholder="••••••••"
                           required>
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <div class="remember-row">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">تذكرني</label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> دخول
            </button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} مدرسة زاد الغد — جميع الحقوق محفوظة
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
