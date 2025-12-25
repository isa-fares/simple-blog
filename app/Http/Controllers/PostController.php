<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Http\Requests\StorePostRequest;
use App\Events\PostCreated;
use App\Services\AuthorizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected AuthorizationService $auth;

    public function __construct()
    {
        $this->auth = new AuthorizationService();
    }

    /**
     * قائمة المقالات المنشورة (للزوار)
     */
    public function index()
    {
        $posts = Post::with('user')
            ->withCount('comments')
            ->where('is_published', true)
            ->latest() 
            ->paginate(12);

        return view('posts.index', compact('posts'));
    }


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

        // استخدام when() للبحث + فلترة اختيارية حسب الكاتب
        $posts = Post::with('user')
            ->where('is_published', true)
            // ✅ استخدام when() للبحث - ينفذ فقط إذا $query موجود
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('title', 'LIKE', "%{$query}%")
                        ->orWhere('content', 'LIKE', "%{$query}%");
                });
            })
            // ✅ فلترة اختيارية حسب الكاتب (مثال إضافي)
            ->when($request->has('author_id'), function ($q) use ($request) {
                $q->where('user_id', $request->author_id);
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
        // التحقق من صلاحية العرض
        if (!$this->auth->canPost('view', $post)) {
            abort(403, 'غير مصرح لك بعرض هذا المقال');
        }

        // تحميل التعليقات مع كتّابها والردود
        $post->load([
            'user',
            'parentComments.user',
            'parentComments.replies.user'
        ]);

        return view('posts.show', compact('post'));
    }

    /**
     * نموذج إنشاء مقال جديد
     */
    public function create()
    {
        // التحقق من صلاحية الإنشاء
        if (!$this->auth->canPost('create')) {
            abort(403, 'غير مصرح لك بإنشاء مقالات');
        }

        return view('posts.create');
    }

    /**
     * حفظ مقال جديد
     */
    public function store(StorePostRequest $request)
    {
        // التحقق من صلاحية الإنشاء
        if (!$this->auth->canPost('create')) {
            abort(403, 'غير مصرح لك بإنشاء مقالات');
        }

        // رفع الصورة إذا وُجدت
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => $this->getUserId($request),
            'title' => $request->validated()['title'],
            'content' => $request->validated()['content'],
            'is_published' => $request->boolean('is_published'),
            'image' => $data['image'] ?? null,
        ]);

        event(new PostCreated($post));

        return redirect()->route('dashboard')->with('success', 'تم إنشاء المقال بنجاح');
    }

    /**
     * نموذج تعديل مقال
     */
    public function edit(Request $request, Post $post)
    {
        // التحقق من صلاحية التعديل
        if (!$this->auth->canPost('update', $post)) {
            abort(403, 'غير مصرح لك بتعديل هذا المقال');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * تحديث مقال
     */
    public function update(Request $request, Post $post)
    {
        // التحقق من صلاحية التعديل
        if (!$this->auth->canPost('update', $post)) {
            abort(403, 'غير مصرح لك بتعديل هذا المقال');
        }
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وُجدت
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'is_published' => $request->boolean('is_published'),
            'image' => $data['image'] ?? $post->image,
        ]);

        return redirect()->route('dashboard')->with('success', 'تم تحديث المقال بنجاح');
    }

    /**
     * حذف مقال
     */
    public function destroy(Request $request, Post $post)
    {
        // التحقق من صلاحية الحذف
        if (!$this->auth->canPost('delete', $post)) {
            abort(403, 'غير مصرح لك بحذف هذا المقال');
        }

        $post->delete();

        return redirect()->route('dashboard')->with('success', 'تم حذف المقال بنجاح');
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
