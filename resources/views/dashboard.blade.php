<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
@if(session('success'))
    <div style="background: #d4edda; padding: 10px; margin-bottom: 10px;">
        {{ session('success') }}
    </div>
@endif

    <h1> {{ $user->name }}</h1>
    <p>: {{ $user->role }}</p>

    <hr>

    <h3>مقالاتي</h3>
<p><a href="{{ route('posts.create') }}">+ إنشاء مقال جديد</a></p>
    @if($posts->count() > 0)
        <table border="1" cellpadding="10">
            <tr>
                <th>#</th>
                <th>العنوان</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                @if($user->isAdmin())<th>المؤلف</th>@endif

                <th>الإجراءات</th>
            </tr>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->is_published ? 'منشور' : 'مسودة' }}</td>
                <td>{{ $post->created_at->format('Y-m-d') }}</td>
                @if($user->isAdmin())<td>{{ $post->user->name }}</td>@endif
                <td>
                    <a href="#">عرض</a> |
                    <a href="{{ route('posts.edit', $post) }}">تعديل</a> |
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('هل أنت متأكد من حذف هذا المقال؟')">حذف</button>
                        </form>
                </td>
            </tr>
            @endforeach
        </table>

        {{-- روابط الصفحات --}}
        <div style="margin-top: 20px;">
            {{ $posts->links() }}
        </div>
    @else
        <p>لا توجد لديك مقالات بعد.</p>
    @endif

    <hr>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">تسجيل الخروج</button>
    </form>
</body>
</html>
