<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessPostCreated implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * المقال الذي تم إنشاؤه
     */
    public Post $post;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     * هذه الدالة يتم تنفيذها تلقائياً من Queue Worker
     */
    public function handle(): void
    {
        Log::info('🔄 Job: بدء معالجة مقال جديد', [
            'post_id' => $this->post->id,
            'title' => $this->post->title,
        ]);

        // مسح الـ Cache المتعلق بقائمة المقالات
        // لأن المقال الجديد لازم يظهر في القائمة
        Cache::forget('posts.index');
        Cache::forget('posts.published');
        
        // يمكن إضافة المزيد من المهام هنا:
        // - إرسال إشعارات
        // - تحديث إحصائيات
        // - إرسال إيميلات

        Log::info('✅ Job: تمت معالجة المقال بنجاح', [
            'post_id' => $this->post->id,
        ]);
    }
}
