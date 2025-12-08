<?php

namespace App\Services;

use App\Models\User;
use App\Models\Post;
use App\Policies\PostPolicy;

class AuthorizationService
{
    protected ?User $user = null;
    protected PostPolicy $postPolicy;

    public function __construct()
    {
        $this->postPolicy = new PostPolicy();
        $this->loadUser();
    }

    /**
     * تحميل المستخدم من الـ Session
     */
    protected function loadUser(): void
    {
        $userId = session('user_id');
        if ($userId) {
            $this->user = User::find($userId);
        }
    }

    /**
     * الحصول على المستخدم الحالي
     */
    public function user(): ?User
    {
        return $this->user;
    }

    /**
     * هل المستخدم مسجل دخوله؟
     */
    public function check(): bool
    {
        return $this->user !== null;
    }

    /**
     * التحقق من صلاحية على المقال
     */
    public function canPost(string $ability, ?Post $post = null): bool
    {
        return match ($ability) {
            // العرض متاح للجميع (حتى الزوار)
            'viewAny' => $this->postPolicy->viewAny($this->user),
            'view' => $post ? $this->postPolicy->view($this->user, $post) : false,

            // الإنشاء والتعديل والحذف يتطلب تسجيل دخول
            'create' => $this->user ? ($this->checkBefore('create') ?? $this->postPolicy->create($this->user)) : false,
            'update' => $this->user && $post ? ($this->checkBefore('update') ?? $this->postPolicy->update($this->user, $post)) : false,
            'delete' => $this->user && $post ? ($this->checkBefore('delete') ?? $this->postPolicy->delete($this->user, $post)) : false,
            default => false,
        };
    }

    /**
     * فحص before (للأدمن)
     */
    protected function checkBefore(string $ability): ?bool
    {
        return $this->postPolicy->before($this->user, $ability);
    }
}
