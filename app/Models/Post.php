<?php

namespace App\Models;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_published',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * كل التعليقات على المقال
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * التعليقات الرئيسية فقط (بدون الردود)
     */
    public function parentComments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
}
