<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 📝 Posts API Controller
 *
 * CRUD للمقالات عبر API مع Authorization
 */
class PostController extends Controller
{
    /**
     * 📋 عرض كل المقالات المنشورة (Public)
     *
     * GET /api/posts
     */
    public function index()
    {
        Log::info('🔍 API: جلب قائمة المقالات', [
            'user_id' => auth()->id(),
            'ip' => request()->ip()
        ]);

        $posts = Post::where('is_published', true)
            ->with('user:id,name,email') // Eager Loading
            ->latest()
            ->paginate(10);

        return PostResource::collection($posts);
    }

    /**
     * 👁️ عرض مقال واحد (Public)
     *
     * GET /api/posts/{id}
     */
    public function show(Post $post)
    {
        // التحقق: هل منشور أو المستخدم هو صاحبه؟
        if (!$post->is_published && $post->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح لك بعرض هذا المقال'
            ], 403);
        }

        $post->load('user:id,name,email');
        return new PostResource($post);
    }

    /**
     * ✏️ إنشاء مقال جديد (يتطلب Token)
     *
     * POST /api/posts
     */
    public function store(Request $request)
    {
        // التحقق: فقط admin و writer
        if (!in_array(auth()->user()->role, ['admin', 'writer'])) {
            return response()->json([
                'message' => 'غير مصرح لك بإنشاء مقالات'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'is_published' => 'boolean',
        ]);

        $post = auth()->user()->posts()->create($validated);

        return new PostResource($post);
    }

    /**
     * 🔄 تعديل مقال (يتطلب Token)
     *
     * PUT /api/posts/{id}
     */
    public function update(Request $request, Post $post)
    {
        // التحقق: admin يعدل أي شي، writer يعدل مقالاته فقط
        if (auth()->user()->role !== 'admin' && $post->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح لك بتعديل هذا المقال'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|min:3|max:255',
            'content' => 'sometimes|string|min:10',
            'is_published' => 'sometimes|boolean',
        ]);

        $post->update($validated);

        return new PostResource($post);
    }

    /**
     * 🗑️ حذف مقال (يتطلب Token)
     *
     * DELETE /api/posts/{id}
     */
    public function destroy(Post $post)
    {
        // التحقق: admin يحذف أي شي، writer يحذف مقالاته فقط
        if (auth()->user()->role !== 'admin' && $post->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح لك بحذف هذا المقال'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'تم حذف المقال بنجاح'
        ]);
    }

    /**
     * 📝 مقالات المستخدم الحالي (يتطلب Token)
     *
     * GET /api/my-posts
     */
    public function myPosts()
    {
        $posts = auth()->user()->posts()
            ->latest()
            ->paginate(10);

        return PostResource::collection($posts);
    }
}
