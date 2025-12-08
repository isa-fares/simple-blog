<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * 🔓 قبل كل الفحوصات - الأدمن يقدر على كل شيء
     * لو رجعت true = مسموح بدون فحص باقي الدوال
     * لو رجعت null = أكمل الفحص العادي
     */
    public function before(?User $user, string $ability): ?bool
    {
        if ($user?->isAdmin()) {
            return true; // الأدمن مسموح له كل شيء
        }

        return null; // أكمل الفحص للباقي
    }

    /**
     * هل يمكن عرض قائمة المقالات؟
     * ✅ الكل يقدر يشوف القائمة (حتى الزوار)
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * هل يمكن عرض مقال معين؟
     * ✅ الكل يقدر يشوف المقالات المنشورة (حتى الزوار)
     * ✅ الكاتب يشوف مقالاته حتى لو مسودة
     */
    public function view(?User $user, Post $post): bool
    {
        // المقال منشور = الكل يشوفه (حتى الزوار)
        if ($post->is_published) {
            return true;
        }

        // المقال مسودة = فقط صاحبه يشوفه
        return $user && $user->id === $post->user_id;
    }

    /**
     * هل يمكن إنشاء مقال جديد؟
     * ✅ Admin و Writer فقط
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'writer']);
    }

    /**
     * هل يمكن تعديل مقال؟
     * ✅ Admin = كل المقالات
     * ✅ Writer = مقالاته فقط
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * هل يمكن حذف مقال؟
     * ✅ Admin = كل المقالات
     * ✅ Writer = مقالاته فقط
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * هل يمكن استعادة مقال محذوف؟ (Soft Delete)
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * هل يمكن الحذف النهائي؟
     *  فقط الأدمن (يتم التحقق في before)
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false; // الأدمن فقط (عبر before)
    }
}
