<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

// ==========================================
// 🌐 مسارات عامة (بدون حماية)
// ==========================================

// تسجيل الدخول - يرجع Token
Route::post('/login', [AuthController::class, 'login']);

// المقالات المنشورة (عامة - بدون Token)
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

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
    
    // Posts CRUD (محمي)
    Route::get('/my-posts', [PostController::class, 'myPosts']); // مقالاتي
    Route::post('/posts', [PostController::class, 'store']); // إنشاء
    Route::put('/posts/{post}', [PostController::class, 'update']); // تعديل
    Route::delete('/posts/{post}', [PostController::class, 'destroy']); // حذف
});
