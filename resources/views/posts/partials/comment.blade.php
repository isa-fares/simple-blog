@php
    $isReply = $isReply ?? false;
@endphp

<div class="comment {{ $isReply ? 'reply' : '' }}">
    <div class="comment-header">
        <span class="comment-author">
            {{ $isReply ? '↩️' : '👤' }} {{ $comment->user->name ?? 'مجهول' }}
        </span>
        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
    </div>
    
    <div class="comment-body">
        {{ $comment->body }}
    </div>
    
    <div class="comment-actions">
        {{-- زر الرد (فقط للتعليقات الرئيسية) --}}
        @if(!$isReply && session('user_id'))
            <a href="javascript:void(0)" onclick="toggleReplyForm({{ $comment->id }})">↩️ رد</a>
        @endif
        
        {{-- تعديل (لصاحب التعليق فقط) --}}
        @if(session('user_id') === $comment->user_id)
            <a href="javascript:void(0)" onclick="toggleEditForm({{ $comment->id }})">✏️ تعديل</a>
        @endif
        
        {{-- حذف (لصاحب التعليق أو الأدمن) --}}
        @php
            $currentUser = \App\Models\User::find(session('user_id'));
        @endphp
        @if(session('user_id') === $comment->user_id || $currentUser?->isAdmin())
            <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('حذف التعليق؟')">🗑️ حذف</button>
            </form>
        @endif
    </div>
    
    {{-- فورم الرد (مخفي افتراضياً) --}}
    @if(!$isReply && session('user_id'))
        <div id="reply-form-{{ $comment->id }}" class="reply-form">
            <form action="{{ route('comments.store', $comment->post_id) }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <textarea name="body" placeholder="اكتب ردك..." style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ddd;"></textarea>
                <button type="submit" style="margin-top: 5px; padding: 5px 15px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    إرسال الرد
                </button>
            </form>
        </div>
    @endif
    
    {{-- فورم التعديل (مخفي افتراضياً) --}}
    @if(session('user_id') === $comment->user_id)
        <div id="edit-form-{{ $comment->id }}" class="reply-form">
            <form action="{{ route('comments.update', $comment) }}" method="POST">
                @csrf
                @method('PUT')
                <textarea name="body" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ddd;">{{ $comment->body }}</textarea>
                <button type="submit" style="margin-top: 5px; padding: 5px 15px; background: #ffc107; color: #333; border: none; border-radius: 5px; cursor: pointer;">
                    حفظ التعديل
                </button>
                <button type="button" onclick="toggleEditForm({{ $comment->id }})" style="margin-top: 5px; padding: 5px 15px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    إلغاء
                </button>
            </form>
        </div>
    @endif
</div>

<script>
function toggleEditForm(commentId) {
    const form = document.getElementById('edit-form-' + commentId);
    form.classList.toggle('show');
}
</script>
