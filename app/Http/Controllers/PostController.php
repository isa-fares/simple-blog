<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Http\Requests\StorePostRequest;
use App\Events\PostCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * قائمة المقالات المنشورة (للزوار)
     */
    public function index()
    {
        // Cache لمدة 5 دقائق (300 ثانية)
        $posts = Cache::remember('posts_index', 300, function () {
            return Post::with('user')
                ->where('is_published', true)
                ->latest()
                ->get();
        });

        return view('posts.index', compact('posts'));
    }

    /**
     * البحث في المقالات
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        // إذا لم يكن هناك كلمة بحث
        if (!$query) {
            return view('posts.search', [
                'posts' => collect([]),
                'query' => '',
            ]);
        }

        // البحث في العنوان والمحتوى
        $posts = Post::with('user')
            ->where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->paginate(10);

        return view('posts.search', compact('posts', 'query'));
    }

    /**
     * عرض مقال واحد
     */
    public function show(Post $post)
    {
        abort_unless($post->is_published, 404);

        // تحميل التعليقات مع كتّابها والردود
        $post->load([
            'user',
            'parentComments.user',      // التعليقات الرئيسية مع كتّابها
            'parentComments.replies.user' // الردود مع كتّابها
        ]);

        return view('posts.show', compact('post'));
    }

    /**
     * نموذج إنشاء مقال جديد
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * حفظ مقال جديد
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create([
            'user_id' => $this->getUserId($request),
            'title' => $request->validated()['title'],
            'content' => $request->validated()['content'],
            'is_published' => $request->boolean('is_published'),
        ]);

        // إطلاق الحدث - الـ Listeners ستتولى الباقي!
        event(new PostCreated($post));

        return redirect()->route('dashboard')->with('success', 'تم إنشاء المقال بنجاح');
    }

    /**
     * نموذج تعديل مقال
     */
    public function edit(Request $request, Post $post)
    {
        $this->authorizePost($request, $post);

        return view('posts.edit', compact('post'));
    }

    /**
     * تحديث مقال
     */
    public function update(Request $request, Post $post)
    {
        $this->authorizePost($request, $post);

        $post->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('dashboard')->with('success', 'تم تحديث المقال بنجاح');
    }

    /**
     * حذف مقال
     */
    public function destroy(Request $request, Post $post)
    {
        $this->authorizePost($request, $post);

        $post->delete();

        return redirect()->route('dashboard')->with('success', 'تم حذف المقال بنجاح');
    }

    /**
     * التحقق من صلاحية الوصول للمقال
     */
    private function authorizePost(Request $request, Post $post): void
    {
        $user = $this->getUser($request);

        if ($post->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'غير مصرح لك');
        }
    }

    /**
     * جلب المستخدم الحالي
     */
    private function getUser(Request $request): User
    {
        return User::findOrFail($request->session()->get('user_id'));
    }

    /**
     * جلب ID المستخدم الحالي
     */
    private function getUserId(Request $request): int
    {
        return $request->session()->get('user_id');
    }
}
