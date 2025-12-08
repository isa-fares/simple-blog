<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل المقال</title>
</head>
<body>
    <h1>تعديل المقال</h1>

    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 10px;">
            <label>العنوان</label><br>
            <input type="text" name="title" value="{{ $post->title }}" style="width: 300px;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>المحتوى</label><br>
            <textarea name="content" rows="6" style="width: 300px;">{{ $post->content }}</textarea>
        </div>

        <div style="margin-bottom: 10px;">
            <label>
                <input type="checkbox" name="is_published" value="1" {{ $post->is_published ? 'checked' : '' }}>
                نشر المقال
            </label>
        </div>

        <button type="submit">تحديث المقال</button>
    </form>

    <br>
    <a href="{{ route('dashboard') }}">العودة للوحة التحكم</a>
</body>
</html>
