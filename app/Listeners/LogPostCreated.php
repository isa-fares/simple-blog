<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Jobs\ProcessPostCreated;
use Illuminate\Support\Facades\Log;

class LogPostCreated
{
    /**
     * معالجة الحدث
     * يتم استدعاء هذه الدالة تلقائياً عند إطلاق PostCreated
     */
    public function handle(PostCreated $event): void
    {
        // $event->post يحتوي على المقال الذي أُنشئ
        Log::info('مقال جديد تم إنشاؤه', [
            'post_id' => $event->post->id,
            'title' => $event->post->title,
            'author_id' => $event->post->user_id,
            'created_at' => $event->post->created_at,
        ]);

        // إرسال Job للـ Queue لمعالجة المقال في الخلفية
        // هذا يعني: "يا Laravel، شغل ProcessPostCreated في الخلفية"
        ProcessPostCreated::dispatch($event->post);
    }
}
