<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المقالات</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: #333;
            font-size: 28px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        /* Search Box */
        .search-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .search-box form {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
        }

        /* Posts Grid */
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .post-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        .post-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: white;
        }

        .post-card-header h3 {
            font-size: 18px;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .post-card-header h3 a {
            color: white;
            text-decoration: none;
        }

        .post-card-header h3 a:hover {
            text-decoration: underline;
        }

        .post-meta {
            font-size: 13px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .post-card-body {
            padding: 20px;
        }

        .post-excerpt {
            color: #666;
            line-height: 1.7;
            font-size: 14px;
        }

        .post-card-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .post-stats {
            display: flex;
            gap: 15px;
            font-size: 13px;
            color: #999;
        }

        .read-more {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s;
        }

        .read-more:hover {
            color: #764ba2;
        }

        /* Empty State */
        .empty-state {
            background: white;
            padding: 60px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .empty-state-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 20px;
        }

        /* Pagination - Custom */
        .pagination-wrapper {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .pagination-info {
            color: white;
            font-size: 14px;
            opacity: 0.9;
        }

        .custom-pagination {
            display: flex;
            gap: 8px;
            background: white;
            padding: 12px 20px;
            border-radius: 50px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            align-items: center;
        }

        .custom-pagination a,
        .custom-pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 8px;
            text-decoration: none;
            color: #555;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .custom-pagination a:hover {
            background: #667eea;
            color: white;
            transform: scale(1.05);
        }

        .custom-pagination .active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .custom-pagination .disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        .custom-pagination .nav-btn {
            background: #f5f5f5;
            font-size: 16px;
        }

        .custom-pagination .nav-btn:hover:not(.disabled) {
            background: #667eea;
            color: white;
        }

        .custom-pagination .dots {
            color: #999;
        }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

<div class="container">
    {{-- Header --}}
    <div class="header">
        <h1>📝 المقالات</h1>
        <div class="header-actions">
            <a href="{{ route('posts.create') }}" class="btn btn-primary">✍️ مقال جديد</a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">🏠 لوحة التحكم</a>
        </div>
    </div>

    {{-- رسالة النجاح --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- صندوق البحث --}}
    <div class="search-box">
        <form action="{{ route('posts.search') }}" method="GET">
            <input type="text" name="q" placeholder="🔍 ابحث عن مقال...">
            <button type="submit" class="btn btn-primary">بحث</button>
        </form>
    </div>

    {{-- شبكة المقالات --}}
    @forelse ($posts as $post)
        @if($loop->first)
            <div class="posts-grid">
        @endif

        <div class="post-card">
            <div class="post-card-header">
                <h3>
                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                </h3>
                <div class="post-meta">
                    <span>👤 {{ $post->user?->name ?? 'مجهول' }}</span>
                    <span>📅 {{ $post->created_at->format('Y/m/d') }}</span>
                </div>
            </div>

            <div class="post-card-body">
                <p class="post-excerpt">
                    {{ \Illuminate\Support\Str::limit($post->content, 120) }}
                </p>
            </div>

            <div class="post-card-footer">
                <div class="post-stats">
                    <span>💬 {{ $post->comments_count ?? $post->comments->count() }}</span>
                    <span>⏱️ {{ $post->created_at->diffForHumans() }}</span>
                </div>
                <a href="{{ route('posts.show', $post) }}" class="read-more">اقرأ المزيد ←</a>
            </div>
        </div>

        @if($loop->last)
            </div>
        @endif
    @empty
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>لا توجد مقالات بعد</h3>
            <p>كن أول من يكتب مقالاً!</p>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">✍️ اكتب مقالاً الآن</a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($posts->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                عرض {{ $posts->firstItem() }} - {{ $posts->lastItem() }} من {{ $posts->total() }} مقال
            </div>
            <div class="custom-pagination">
                {{-- Previous --}}
                @if($posts->onFirstPage())
                    <span class="nav-btn disabled">‹</span>
                @else
                    <a href="{{ $posts->previousPageUrl() }}" class="nav-btn">‹</a>
                @endif

                {{-- Page Numbers --}}
                @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                    @if($page == $posts->currentPage())
                        <span class="active">{{ $page }}</span>
                    @elseif($page == 1 || $page == $posts->lastPage() || abs($page - $posts->currentPage()) <= 2)
                        <a href="{{ $url }}">{{ $page }}</a>
                    @elseif(abs($page - $posts->currentPage()) == 3)
                        <span class="dots">...</span>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($posts->hasMorePages())
                    <a href="{{ $posts->nextPageUrl() }}" class="nav-btn">›</a>
                @else
                    <span class="nav-btn disabled">›</span>
                @endif
            </div>
        </div>
    @endif
</div>

</body>
</html>
