 <h2>كل المقالات المنشورة</h2>
    @forelse ($posts as $post)
        <div class="post">
            <h3>
                <a href="{{ route('posts.show', $post) }}">
                    {{ $post->title }}
                </a>
            </h3>
            <div class="meta">
                بواسطة: {{ $post->user?->name ?? 'مستخدم مجهول' }} |
                بتاريخ: {{ $post->created_at->format('Y-m-d H:i') }}
            </div>
            <p>
                {{ \Illuminate\Support\Str::limit($post->content, 100) }}
            </p>
        </div>
    @empty
        <p>لا توجد مقالات حالياً.</p>
    @endforelse
