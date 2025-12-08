<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * إضافة تعليق جديد على مقال
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|min:3|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->comments()->create([
            'user_id' => session('user_id'),
            'parent_id' => $request->parent_id,
            'body' => $request->body,
        ]);

        return back()->with('success', 'تم إضافة التعليق بنجاح');
    }

    /**
     * تعديل تعليق
     */
    public function update(Request $request, Comment $comment)
    {
        // التأكد أن المستخدم هو صاحب التعليق
        if ($comment->user_id !== session('user_id')) {
            return back()->with('error', 'لا يمكنك تعديل هذا التعليق');
        }

        $request->validate([
            'body' => 'required|min:3|max:1000',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return back()->with('success', 'تم تعديل التعليق بنجاح');
    }

    /**
     * حذف تعليق
     */
    public function destroy(Comment $comment)
    {
        // التأكد أن المستخدم هو صاحب التعليق أو admin
        $userId = session('user_id');
        $user = \App\Models\User::find($userId);

        if ($comment->user_id !== $userId && !$user?->isAdmin()) {
            return back()->with('error', 'لا يمكنك حذف هذا التعليق');
        }

        $comment->delete(); // سيحذف الردود تلقائياً (cascade)

        return back()->with('success', 'تم حذف التعليق بنجاح');
    }
}
