<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // عرض صفحة Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // معالجة Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
            ])->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'تم تسجيل الخروج بنجاح.');
    }

    // Refresh Token (لـ Sanctum API Tokens)
    public function refreshToken(Request $request)
    {
        $user = $request->user();

        // حذف التوكن الحالي وإنشاء واحد جديد
        $user->currentAccessToken()->delete();
        $newToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'تم تحديث التوكن بنجاح',
            'access_token' => $newToken,
            'token_type'   => 'Bearer',
        ]);
    }
}
