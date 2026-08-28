<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\payment_sys\SchoolClassController as Payment_sysSchoolClassController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\students\StudentController;
use App\Http\Controllers\students\StudentRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Public
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ============================================
// Protected Routes - يتطلب تسجيل دخول فقط
// ============================================
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

 // Export Students to Excel
     Route::get('/students/export-excel', [StudentController::class, 'exportExcel'])->name('students.export-excel');

    // ============================================
    // معالج التسجيل الشامل (طالب + ولي أمر + تسجيل + خصومات)
    // ملاحظة: يجب تسجيلها قبل Route::resource('students', ...) وإلا
    // سيطابقها الـ resource كـ students/{student} برقم "register"
    // ============================================
    Route::get('/students/register', [StudentRegistrationController::class, 'create'])->name('students.register');
    Route::post('/students/register', [StudentRegistrationController::class, 'store'])->name('students.register.store');
    Route::get('/parents/lookup', [StudentRegistrationController::class, 'lookupParent'])->name('parents.lookup');
    Route::get('/students/register/check-discounts', [StudentRegistrationController::class, 'checkDiscounts'])->name('students.register.check-discounts');

    // Students Management
    // ملاحظة: create/store مستثناة لأن إنشاء الطالب صار حصراً عبر
    // معالج التسجيل الشامل (students.register) — راجع StudentRegistrationController
    Route::resource('students', StudentController::class)->except(['create', 'store']);
    Route::resource('classes', Payment_sysSchoolClassController::class);
    // ملاحظة: Route::resource ينشئ تلقائياً:
// GET    /classes              → index
// GET    /classes/create       → create
// POST   /classes              → store
// GET    /classes/{class}      → show
// GET    /classes/{class}/edit → edit
// PUT    /classes/{class}      → update
// DELETE /classes/{class}      → destroy

    // ============================================
    // أولياء الأمور (بدون create/store — الإنشاء فقط عبر معالج التسجيل)
    // ============================================
    Route::resource('parents', ParentController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);

    // ============================================
    // التسجيلات (Registrations)
    // ============================================
    Route::get('/students/{student}/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::get('/registrations/{registration}/edit', [RegistrationController::class, 'edit'])->name('registrations.edit');
    Route::put('/registrations/{registration}', [RegistrationController::class, 'update'])->name('registrations.update');
    Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');

    // ============================================
    // الدفعات المالية (Payments)
    // ============================================
    Route::get('/registrations/{registration}/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/registrations/{registration}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/registrations/{registration}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // ============================================
    // إدارة أنواع الخصومات (Discounts)
    // ============================================
    Route::patch('/discounts/{discount}/toggle-active', [DiscountController::class, 'toggleActive'])->name('discounts.toggle-active');
    Route::resource('discounts', DiscountController::class)->except(['show']);




});


// Refresh Token (Sanctum)
Route::middleware('auth:sanctum')->post('/api/token/refresh', [AuthController::class, 'refreshToken']);
