<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 📝 Post API Resource
 *
 * تنسيق بيانات المقالات للـ API
 */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'is_published' => (bool) $this->is_published,
            'excerpt' => $this->getExcerpt(), // أول 100 حرف

            // معلومات الكاتب
            'author' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],

            // التواريخ
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
            'published_since' => $this->created_at->diffForHumans(), // "منذ 5 أيام"
        ];
    }

    /**
     * استخراج أول 100 حرف من المحتوى
     */
    private function getExcerpt(): string
    {
        return strlen($this->content) > 100
            ? substr($this->content, 0, 100) . '...'
            : $this->content;
    }
}
