<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
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

        /* Header Card */
        .header-card {
            background: white;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .user-details h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }

        .user-role {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .role-admin {
            background: #fee2e2;
            color: #dc2626;
        }

        .role-writer {
            background: #dbeafe;
            color: #2563eb;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-icon {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        /* Main Card */
        .main-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            font-size: 20px;
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        tr:hover {
            background: #f8f9fa;
        }

        td {
            color: #555;
            font-size: 14px;
        }

        .post-title {
            font-weight: 500;
            color: #333;
            max-width: 250px;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-published {
            background: #d4edda;
            color: #155724;
        }

        .status-draft {
            background: #fff3cd;
            color: #856404;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .action-view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .action-edit {
            background: #fff8e1;
            color: #f57c00;
        }

        .action-delete {
            background: #ffebee;
            color: #d32f2f;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .empty-state-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 20px 25px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: center;
        }

        .custom-pagination {
            display: flex;
            gap: 5px;
        }

        .custom-pagination a,
        .custom-pagination span {
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            color: #555;
            font-size: 14px;
            transition: all 0.3s;
        }

        .custom-pagination a:hover {
            background: #667eea;
            color: white;
        }

        .custom-pagination .active {
            background: #667eea;
            color: white;
        }

        .custom-pagination .disabled {
            color: #ccc;
        }

        /* Tabs */
        .tabs-container {
            margin-bottom: 25px;
        }

        .tabs-header {
            display: flex;
            gap: 5px;
            background: white;
            padding: 5px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .tab-btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            color: #666;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-btn:hover {
            background: #f0f0f0;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Logs */
        .log-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            border-right: 4px solid #667eea;
            transition: all 0.3s;
        }

        .log-item:hover {
            background: #f0f0f0;
        }

        .log-item.log-error {
            border-right-color: #dc3545;
            background: #fff5f5;
        }

        .log-item.log-warning {
            border-right-color: #ffc107;
            background: #fffdf5;
        }

        .log-item.log-info {
            border-right-color: #17a2b8;
            background: #f5fbff;
        }

        .log-item.log-debug {
            border-right-color: #6c757d;
        }

        .log-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .log-level {
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .level-error {
            background: #fee2e2;
            color: #dc2626;
        }

        .level-warning {
            background: #fef3c7;
            color: #d97706;
        }

        .level-info {
            background: #dbeafe;
            color: #2563eb;
        }

        .level-debug {
            background: #e5e7eb;
            color: #4b5563;
        }

        .log-time {
            font-size: 12px;
            color: #999;
        }

        .log-message {
            font-size: 14px;
            color: #333;
            word-break: break-word;
            line-height: 1.5;
        }

        .log-message pre {
            margin: 10px 0 0 0;
            padding: 10px;
            background: #1e1e1e;
            color: #d4d4d4;
            border-radius: 5px;
            font-size: 12px;
            overflow-x: auto;
            max-height: 200px;
        }

        .logs-empty {
            text-align: center;
            padding: 50px;
            color: #999;
        }

        .logs-list {
            max-height: 600px;
            overflow-y: auto;
        }

        .tab-badge {
            background: rgba(255,255,255,0.3);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
        }

        .tab-btn.active .tab-badge {
            background: rgba(255,255,255,0.3);
        }

        .tab-btn:not(.active) .tab-badge {
            background: #e0e0e0;
        }
    </style>
</head>
<body>

<div class="container">
    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="header-card">
        <div class="user-info">
            <div class="user-avatar">👤</div>
            <div class="user-details">
                <h1>مرحباً، {{ $user->name }}</h1>
                <span class="user-role {{ $user->role === 'admin' ? 'role-admin' : 'role-writer' }}">
                    {{ $user->role === 'admin' ? '👑 مدير' : '✍️ كاتب' }}
                </span>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">📝 المقالات</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">🚪 خروج</button>
            </form>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-number">{{ $posts->total() }}</div>
            <div class="stat-label">إجمالي المقالات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-number">{{ $user->isAdmin() ? \App\Models\Post::where('is_published', true)->count() : $user->posts()->where('is_published', true)->count() }}</div>
            <div class="stat-label">مقالات منشورة</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-number">{{ $user->isAdmin() ? \App\Models\Post::where('is_published', false)->count() : $user->posts()->where('is_published', false)->count() }}</div>
            <div class="stat-label">مسودات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💬</div>
            <div class="stat-number">{{ \App\Models\Comment::count() }}</div>
            <div class="stat-label">التعليقات</div>
        </div>
    </div>

    {{-- Tabs --}}
    @if($user->isAdmin())
        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab('posts')">
                    📚 المقالات
                    <span class="tab-badge">{{ $posts->total() }}</span>
                </button>
                <button class="tab-btn" onclick="switchTab('logs')">
                    📋 سجل النشاط
                    <span class="tab-badge">{{ count($logs) }}</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Tab: Posts --}}
    <div id="tab-posts" class="tab-content active">
    <div class="main-card">
        <div class="card-header">
            <h2>📚 مقالاتي</h2>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                ➕ مقال جديد
            </a>
        </div>

        <div class="card-body">
            @if($posts->count() > 0)
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                @if($user->isAdmin())
                                    <th>الكاتب</th>
                                @endif
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td class="post-title">{{ Str::limit($post->title, 40) }}</td>
                                    <td>
                                        <span class="status-badge {{ $post->is_published ? 'status-published' : 'status-draft' }}">
                                            {{ $post->is_published ? '✅ منشور' : '📝 مسودة' }}
                                        </span>
                                    </td>
                                    <td>{{ $post->created_at->format('Y/m/d') }}</td>
                                    @if($user->isAdmin())
                                        <td>{{ $post->user->name }}</td>
                                    @endif
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('posts.show', $post) }}" class="action-btn action-view" title="عرض">👁️</a>
                                            <a href="{{ route('posts.edit', $post) }}" class="action-btn action-edit" title="تعديل">✏️</a>
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-delete" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا المقال؟')">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h3>لا توجد مقالات بعد</h3>
                    <p>ابدأ بكتابة أول مقال لك!</p>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary" style="margin-top: 15px;">✍️ اكتب مقالاً</a>
                </div>
            @endif
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div class="pagination-wrapper">
                <div class="custom-pagination">
                    @if($posts->onFirstPage())
                        <span class="disabled">‹</span>
                    @else
                        <a href="{{ $posts->previousPageUrl() }}">‹</a>
                    @endif

                    @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                        @if($page == $posts->currentPage())
                            <span class="active">{{ $page }}</span>
                        @elseif($page == 1 || $page == $posts->lastPage() || abs($page - $posts->currentPage()) <= 2)
                            <a href="{{ $url }}">{{ $page }}</a>
                        @elseif(abs($page - $posts->currentPage()) == 3)
                            <span>...</span>
                        @endif
                    @endforeach

                    @if($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}">›</a>
                    @else
                        <span class="disabled">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
    </div>{{-- End tab-posts --}}

    {{-- Tab: Logs (للأدمن فقط) --}}
    @if($user->isAdmin())
        <div id="tab-logs" class="tab-content">
            <div class="main-card">
                <div class="card-header" style="background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%);">
                    <h2>📋 سجل النشاط</h2>
                    <span style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 20px; font-size: 13px;">
                        آخر {{ count($logs) }} سجل
                    </span>
                </div>

                <div class="card-body">
                    @if(count($logs) > 0)
                        <div class="logs-list">
                            @foreach($logs as $log)
                                @php
                                    $levelClass = match(strtolower($log['level'])) {
                                        'error' => 'log-error',
                                        'warning' => 'log-warning',
                                        'info' => 'log-info',
                                        'debug' => 'log-debug',
                                        default => ''
                                    };
                                    $badgeClass = match(strtolower($log['level'])) {
                                        'error' => 'level-error',
                                        'warning' => 'level-warning',
                                        'info' => 'level-info',
                                        'debug' => 'level-debug',
                                        default => ''
                                    };
                                    // فصل الرسالة عن التفاصيل
                                    $parts = explode("\n", $log['message'], 2);
                                    $mainMessage = $parts[0];
                                    $details = $parts[1] ?? null;
                                @endphp
                                <div class="log-item {{ $levelClass }}">
                                    <div class="log-header">
                                        <span class="log-level {{ $badgeClass }}">{{ $log['level'] }}</span>
                                        <span class="log-time">🕐 {{ $log['timestamp'] }}</span>
                                    </div>
                                    <div class="log-message">
                                        {{ $mainMessage }}
                                        @if($details)
                                            <pre>{{ Str::limit($details, 500) }}</pre>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="logs-empty">
                            <div style="font-size: 50px; margin-bottom: 15px;">📭</div>
                            <h3>لا توجد سجلات</h3>
                            <p>سجل النشاط فارغ حالياً</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function switchTab(tabName) {
    // إخفاء كل المحتوى
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // إزالة active من كل الأزرار
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // إظهار التبويب المحدد
    document.getElementById('tab-' + tabName).classList.add('active');

    // تفعيل الزر
    event.target.closest('.tab-btn').classList.add('active');
}
</script>

</body>
</html>
