<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>البحث في المقالات</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .search-box { margin-bottom: 20px; }
        .search-box input[type="text"] { width: 70%; padding: 10px; font-size: 16px; }
        .search-box button { padding: 10px 20px; font-size: 16px; cursor: pointer; }
        .result { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .result:hover { background: #f9f9f9; }
        .result h3 { margin: 0 0 10px 0; }
        .result h3 a { color: #007bff; text-decoration: none; }
        .result h3 a:hover { text-decoration: underline; }
        .meta { color: #666; font-size: 14px; }
        .highlight { background: yellow; padding: 2px; }
        .no-results { color: #666; text-align: center; padding: 40px; }
        .pagination { margin-top: 20px; }
        .pagination a, .pagination span { padding: 5px 10px; margin: 0 2px; border: 1px solid #ddd; text-decoration: none; }
        .pagination a:hover { background: #f0f0f0; }
    </style>
</head>
<body>

<h1>🔍 البحث في المقالات</h1>

{{-- حقل البحث --}}
<div class="search-box">
    <form action="{{ route('posts.search') }}" method="GET">
        <input type="text" name="q" value="{{ $query }}" placeholder="ابحث عن مقال...">
        <button type="submit">بحث</button>
    </form>
</div>

{{-- عدد النتائج --}}
@if($query)
    <p>نتائج البحث عن: <strong>"{{ $query }}"</strong> ({{ $posts->total() }} نتيجة)</p>
@endif

{{-- النتائج --}}
@forelse($posts as $post)
    <div class="result">
        <h3>
            <a href="{{ route('posts.show', $post) }}">
                {{-- تمييز كلمة البحث في العنوان --}}
                {!! str_ireplace($query, '<span class="highlight">'.$query.'</span>', e($post->title)) !!}
            </a>
        </h3>
        <p class="meta">
            بواسطة: {{ $post->user->name }} |
            {{ $post->created_at->format('Y-m-d') }}
        </p>
        <p>
            {{-- عرض جزء من المحتوى مع تمييز كلمة البحث --}}
            {!! str_ireplace($query, '<span class="highlight">'.$query.'</span>', e(Str::limit($post->content, 200))) !!}
        </p>
    </div>
@empty
    @if($query)
        <div class="no-results">
            <p>😕 لا توجد نتائج لـ "{{ $query }}"</p>
            <p>جرب كلمات بحث أخرى</p>
        </div>
    @else
        <div class="no-results">
            <p>اكتب كلمة للبحث في المقالات</p>
        </div>
    @endif
@endforelse

{{-- Pagination --}}
@if($posts->hasPages())
    <div class="pagination">
        {{ $posts->appends(['q' => $query])->links() }}
    </div>
@endif

<hr>
<a href="{{ route('dashboard') }}">← العودة للوحة التحكم</a>

</body>
</html>
