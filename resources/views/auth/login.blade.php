<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h4 class="text-center mb-4 fw-bold">تسجيل الدخول</h4>

                {{-- رسائل الخطأ --}}
                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- رسالة نجاح --}}
                @if (session('success'))
                    <div class="alert alert-success rounded-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">البريد الإلكتروني</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="example@email.com"
                               required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">كلمة المرور</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="••••••••"
                               required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">تذكرني</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        دخول
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
