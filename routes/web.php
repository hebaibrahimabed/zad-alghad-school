<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\payment_sys\SchoolClassController as Payment_sysSchoolClassController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\students\StudentController;
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


    // Students Management
    Route::resource('students', StudentController::class);
    Route::resource('classes', Payment_sysSchoolClassController::class);
    // ملاحظة: Route::resource ينشئ تلقائياً:
// GET    /classes              → index
// GET    /classes/create       → create
// POST   /classes              → store
// GET    /classes/{class}      → show
// GET    /classes/{class}/edit → edit
// PUT    /classes/{class}      → update
// DELETE /classes/{class}      → destroy




});


// Refresh Token (Sanctum)
Route::middleware('auth:sanctum')->post('/api/token/refresh', [AuthController::class, 'refreshToken']);
