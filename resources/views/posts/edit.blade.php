<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المقال</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        /* Card */
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .post-id {
            background: rgba(255,255,255,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .card-body {
            padding: 30px;
        }
        
        /* Form */
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }
        
        textarea.form-control {
            min-height: 200px;
            resize: vertical;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            cursor: pointer;
        }
        
        .form-check input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .form-check label {
            margin: 0;
            cursor: pointer;
        }
        
        /* Buttons */
        .btn {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(245, 158, 11, 0.4);
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
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .form-actions-right {
            display: flex;
            gap: 15px;
        }
        
        /* Errors */
        .alert-errors {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        
        .alert-errors ul {
            margin: 0;
            padding-right: 20px;
            color: #dc2626;
        }
        
        .error-text {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }
        
        .form-control.is-invalid {
            border-color: #dc2626;
        }
        
        /* Meta Info */
        .meta-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            display: flex;
            gap: 25px;
            font-size: 14px;
            color: #666;
        }
        
        .meta-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-card">
        <div class="card-header">
            <h1>✏️ تعديل المقال</h1>
            <span class="post-id">#{{ $post->id }}</span>
        </div>
        
        <div class="card-body">
            {{-- معلومات إضافية --}}
            <div class="meta-info">
                <span>👤 الكاتب: {{ $post->user->name }}</span>
                <span>📅 تاريخ الإنشاء: {{ $post->created_at->format('Y/m/d') }}</span>
                <span>🔄 آخر تحديث: {{ $post->updated_at->diffForHumans() }}</span>
            </div>
            
            {{-- عرض الأخطاء --}}
            @if($errors->any())
                <div class="alert-errors">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('posts.update', $post) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="title">📌 العنوان</label>
                    <input 
                        type="text" 
                        id="title"
                        name="title" 
                        class="form-control @error('title') is-invalid @enderror" 
                        value="{{ old('title', $post->title) }}"
                        placeholder="أدخل عنوان المقال..."
                    >
                    @error('title')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="content">📝 المحتوى</label>
                    <textarea 
                        id="content"
                        name="content" 
                        class="form-control @error('content') is-invalid @enderror"
                        placeholder="اكتب محتوى المقال هنا..."
                    >{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            id="is_published"
                            name="is_published" 
                            value="1" 
                            {{ old('is_published', $post->is_published) ? 'checked' : '' }}
                        >
                        <label for="is_published">🌐 نشر المقال</label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المقال؟')">
                            🗑️ حذف المقال
                        </button>
                    </form>
                    
                    <div class="form-actions-right">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            ↩️ إلغاء
                        </a>
                        <button type="submit" class="btn btn-warning">
                            💾 تحديث المقال
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
