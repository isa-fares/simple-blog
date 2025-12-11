<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * 🔐 API Authentication Controller
 * 
 * المسؤول عن:
 * - تسجيل دخول API (إصدار Token)
 * - تسجيل خروج API (حذف Token)
 * - إرجاع بيانات المستخدم الحالي
 */
class AuthController extends Controller
{
    /**
     * 🔑 تسجيل الدخول - Login
     * 
     * الخطوات:
     * 1. التحقق من البيانات (email + password)
     * 2. البحث عن المستخدم في قاعدة البيانات
     * 3. التحقق من كلمة المرور
     * 4. إنشاء Token جديد
     * 5. إرجاع Token للمستخدم
     */
    public function login(Request $request)
    {
        // 1️⃣ التحقق من البيانات
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2️⃣ البحث عن المستخدم
        $user = User::where('email', $request->email)->first();

        // 3️⃣ التحقق من كلمة المرور
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البيانات غير صحيحة'],
            ]);
        }

        // 4️⃣ إنشاء Token جديد
        // createToken('اسم الجهاز') - مثلاً: 'mobile-app', 'web-app'
        $token = $user->createToken('api-token')->plainTextToken;

        // 5️⃣ إرجاع Token + بيانات المستخدم
        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token, // 🔑 هذا المهم!
        ]);
    }

    /**
     * 👤 إرجاع بيانات المستخدم الحالي
     * 
     * يتطلب: Authorization Bearer Token
     */
    public function me(Request $request)
    {
        // $request->user() يرجع المستخدم الحالي (من الـ Token)
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    /**
     * 🚪 تسجيل الخروج - Logout
     * 
     * يحذف الـ Token الحالي
     */
    public function logout(Request $request)
    {
        // حذف الـ Token الحالي فقط
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }

    /**
     * 🗑️ حذف كل الـ Tokens (Logout من كل الأجهزة)
     */
    public function logoutAll(Request $request)
    {
        // حذف كل tokens المستخدم
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج من كل الأجهزة',
        ]);
    }
}
