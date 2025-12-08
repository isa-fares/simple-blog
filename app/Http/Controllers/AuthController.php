<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * عدد المحاولات المسموحة قبل الحظر
     */
    private const MAX_ATTEMPTS = 5;

    /**
     * مدة الحظر بالثواني (دقيقة واحدة)
     */
    private const DECAY_SECONDS = 60;

    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * معالجة تسجيل الدخول
     */
    public function login(Request $request)
    {
        // 1. التحقق من المدخلات
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. التحقق من Rate Limiting
        $throttleKey = $this->getThrottleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            Log::warning('محاولات دخول كثيرة', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "محاولات كثيرة. حاول بعد {$seconds} ثانية.",
                ]);
        }

        // 3. البحث عن المستخدم
        $user = User::where('email', $credentials['email'])->first();

        // 4. التحقق من المستخدم وكلمة المرور
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            // زيادة عداد المحاولات الفاشلة
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            // تسجيل المحاولة الفاشلة
            Log::notice('محاولة دخول فاشلة', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'attempts' => RateLimiter::attempts($throttleKey),
            ]);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'بيانات الدخول غير صحيحة.',
                ]);
        }

        // 5. نجاح تسجيل الدخول - مسح عداد المحاولات
        RateLimiter::clear($throttleKey);

        // 6. تجديد الـ Session (حماية من Session Fixation)
        $request->session()->regenerate();

        // 7. حفظ بيانات المستخدم في الـ Session
        $request->session()->put('user_id', $user->id);
        $request->session()->put('login_time', now()->toDateTimeString());
        $request->session()->put('ip_address', $request->ip());

        // 8. تسجيل الدخول الناجح
        Log::info('تسجيل دخول ناجح', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        // تسجيل الخروج
        Log::info('تسجيل خروج', [
            'user_id' => $request->session()->get('user_id'),
        ]);

        // مسح كل بيانات الـ Session
        $request->session()->invalidate();

        // تجديد Token الـ CSRF
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'تم تسجيل الخروج بنجاح');
    }

    /**
     * صفحة الملف الشخصي
     */
    public function me(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $user = $userId ? User::find($userId) : null;

        return view('auth.me', compact('user'));
    }

    /**
     * لوحة التحكم
     */
    public function dashboard(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $user = User::findOrFail($userId);

        $posts = $user->isAdmin()
            ? Post::with('user')->latest()->paginate(15)
            : Post::where('user_id', $userId)->latest()->paginate(15);

        // قراءة آخر سجلات النشاط (للأدمن فقط)
        $logs = [];
        if ($user->isAdmin()) {
            $logs = $this->getRecentLogs(50);
        }

        return view('dashboard', compact('user', 'posts', 'logs'));
    }

    /**
     * قراءة آخر سجلات النشاط من ملف الـ log
     */
    private function getRecentLogs(int $limit = 50): array
    {
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return [];
        }

        $logs = [];
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_reverse($lines); // الأحدث أولاً

        $currentLog = null;
        $count = 0;

        foreach ($lines as $line) {
            // التعرف على بداية log جديد
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.*)$/', $line, $matches)) {
                if ($currentLog && $count < $limit) {
                    $logs[] = $currentLog;
                    $count++;
                }

                $currentLog = [
                    'timestamp' => $matches[1],
                    'level' => $matches[2],
                    'message' => $matches[3],
                ];

                if ($count >= $limit) break;
            } elseif ($currentLog) {
                // إضافة سطر للـ log الحالي (stack trace مثلاً)
                $currentLog['message'] .= "\n" . $line;
            }
        }

        // إضافة آخر log
        if ($currentLog && $count < $limit) {
            $logs[] = $currentLog;
        }

        return $logs;
    }

    /**
     * إنشاء مفتاح فريد للـ Rate Limiting
     * يعتمد على الإيميل + IP
     */
    private function getThrottleKey(Request $request): string
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }
}
