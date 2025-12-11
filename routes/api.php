<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

// ==========================================
// 🌐 مسارات عامة (بدون حماية)
// ==========================================

// تسجيل الدخول - يرجع Token
Route::post('/login', [AuthController::class, 'login']);

// ==========================================
// 🔐 مسارات محمية (تحتاج Token)
// ==========================================

Route::middleware('auth:sanctum')->group(function () {

    // معلومات المستخدم الحالي
    Route::get('/me', [AuthController::class, 'me']);

    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);

    // Users CRUD (محمي)
    Route::apiResource('users', UserController::class);
});
