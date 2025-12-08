<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCreated
{
    use Dispatchable, SerializesModels;

    /**
     * المقال الذي تم إنشاؤه
     */
    public Post $post;

    /**
     * إنشاء حدث جديد
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
