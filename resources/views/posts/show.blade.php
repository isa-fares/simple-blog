<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ $post->title }}</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        .post-card { background: white; padding: 30px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .post-title { margin: 0 0 10px 0; color: #333; }
        .post-meta { color: #666; font-size: 14px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .post-content { line-height: 1.8; color: #444; }

        /* التعليقات */
        .comments-section { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .comments-title { margin: 0 0 20px 0; padding-bottom: 10px; border-bottom: 2px solid #007bff; }

        .comment { background: #f9f9f9; padding: 15px; margin: 10px 0; border-radius: 8px; border-right: 3px solid #007bff; }
        .comment.reply { margin-right: 30px; border-right-color: #28a745; background: #f0fff0; }
        .comment-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .comment-author { font-weight: bold; color: #333; }
        .comment-date { color: #999; font-size: 12px; }
        .comment-body { color: #555; line-height: 1.6; }
        .comment-actions { margin-top: 10px; }
        .comment-actions a, .comment-actions button { font-size: 12px; color: #666; margin-left: 10px; cursor: pointer; background: none; border: none; }
        .comment-actions a:hover, .comment-actions button:hover { color: #007bff; }

        /* فورم التعليق */
        .comment-form { margin: 20px 0; }
        .comment-form textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; resize: vertical; min-height: 80px; }
        .comment-form button { margin-top: 10px; padding: 8px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .comment-form button:hover { background: #0056b3; }

        .reply-form { display: none; margin-top: 10px; padding: 10px; background: #fff; border-radius: 5px; }
        .reply-form.show { display: block; }

        .alert { padding: 10px 15px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }

        .btn { padding: 8px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin-left: 5px; }
        .btn-back { background: #6c757d; color: white; }
        .btn-edit { background: #ffc107; color: #333; }
        .btn-delete { background: #dc3545; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>

{{-- رسائل النجاح/الخطأ --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

{{-- المقال --}}
<div class="post-card">
    <h1 class="post-title">{{ $post->title }}</h1>
    <div class="post-meta">
        ✍️ {{ $post->user->name ?? 'مجهول' }} |
        📅 {{ $post->created_at->format('Y-m-d') }}
    </div>
    @if($post->image)
        <div class="mb-4">
            <img src="{{ asset('storage/' . $post->image) }}"
                alt="{{ $post->title }}"
                class="img-fluid rounded"
                style="max-width: 100%; max-height: 400px; object-fit: cover;">
        </div>
    @endif
    <div class="post-content">
        {!! nl2br(e($post->content)) !!}
    </div>

    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
        <a href="{{ route('posts.index') }}" class="btn btn-back">← العودة للمقالات</a>
        @if(session('user_id') === $post->user_id)
            <a href="{{ route('posts.edit', $post) }}" class="btn btn-edit">✏️ تعديل</a>
            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete" onclick="return confirm('هل أنت متأكد؟')">🗑️ حذف</button>
            </form>
        @endif
    </div>
</div>

{{-- قسم التعليقات --}}
<div class="comments-section">
    <h2 class="comments-title">💬 التعليقات ({{ $post->comments->count() }})</h2>

    {{-- فورم إضافة تعليق جديد --}}
    @if(session('user_id'))
        <div class="comment-form">
            <form action="{{ route('comments.store', $post) }}" method="POST">
                @csrf
                <textarea name="body" placeholder="اكتب تعليقك هنا..." required>{{ old('body') }}</textarea>
                @error('body')
                    <div style="color: red; font-size: 12px;">{{ $message }}</div>
                @enderror
                <button type="submit">إضافة تعليق</button>
            </form>
        </div>
    @else
        <p style="color: #666; text-align: center; padding: 20px;">
            <a href="{{ route('login') }}">سجل دخولك</a> لإضافة تعليق
        </p>
    @endif

    {{-- عرض التعليقات الرئيسية مع الردود --}}
    @forelse($post->parentComments as $comment)
        {{-- التعليق الرئيسي --}}
        @include('posts.partials.comment', ['comment' => $comment])

        {{-- الردود على هذا التعليق --}}
        @foreach($comment->replies as $reply)
            @include('posts.partials.comment', ['comment' => $reply, 'isReply' => true])
        @endforeach
    @empty
        <p style="color: #999; text-align: center; padding: 30px;">
            لا توجد تعليقات بعد. كن أول من يعلق! 🎉
        </p>
    @endforelse
</div>

<script>
// إظهار/إخفاء فورم الرد
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    form.classList.toggle('show');
}
</script>

</body>
</html>
