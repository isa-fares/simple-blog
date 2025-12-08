<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'parent_id', 'body'];

    /**
     * التعليق يتبع مقال
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * التعليق يتبع مستخدم (كاتب التعليق)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * التعليق الأب (لو كان هذا رد)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * الردود على هذا التعليق
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * هل هذا تعليق رئيسي؟
     */
    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * هل هذا رد على تعليق؟
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }
}
