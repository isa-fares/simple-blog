<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إنشاء مقال جديد</title>
</head>
<body>
    <h1>إنشاء مقال جديد</h1>

    {{-- عرض رسائل الأخطاء --}}
    @if($errors->any())
        <div style="background: #f8d7da; padding: 10px; margin-bottom: 10px; color: #721c24;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 10px;">
            <label>العنوان</label><br>
            <input type="text" name="title" value="{{ old('title') }}" style="width: 300px;">
            @error('title')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 10px;">
            <label>المحتوى</label><br>
            <textarea name="content" rows="6" style="width: 300px;">{{ old('content') }}</textarea>
            @error('content')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 10px;">
            <label>
                <input type="checkbox" name="is_published" value="1" checked>
                نشر المقال
            </label>
        </div>

        <button type="submit">حفظ المقال</button>
    </form>

    <br>
    <a href="{{ route('dashboard') }}">العودة للوحة التحكم</a>
</body>
</html>
