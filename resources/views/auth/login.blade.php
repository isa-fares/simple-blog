<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <style>
        body { font-family: sans-serif; max-width: 400px; margin: 50px auto; padding: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        input { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; margin-top: 15px; }
        button:hover { background: #0056b3; }
        label { font-weight: bold; }
    </style>
</head>
<body>

<h2>تسجيل الدخول</h2>

{{-- رسالة النجاح (مثل بعد تسجيل الخروج) --}}
@if(session('status'))
    <div class="success">{{ session('status') }}</div>
@endif

{{-- رسائل الخطأ --}}
@if($errors->any())
    <div class="error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form action="{{ route('login.attempt') }}" method="POST">
    @csrf

    <div style="margin-bottom: 15px;">
        <label>البريد الإلكتروني</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div style="margin-bottom: 15px;">
        <label>كلمة المرور</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit">دخول</button>
</form>

</body>
</html>
